<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserPin;
use App\Models\TrustedDevice;
use Illuminate\Support\Facades\Hash;
use App\Services\DeviceFingerprintService;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        // Create PIN record for user
        UserPin::create([
            'user_id' => $user->id,
            'is_set' => false,
        ]);

        Auth::login($user);
        return redirect()->route('setup-pin')->with('success', 'Account created successfully! Please set your PIN.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, remember: false)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $deviceFingerprint = DeviceFingerprintService::generate($request);

            // Check if this device is trusted
            $trustedDevice = TrustedDevice::where('user_id', $user->id)
                ->where('device_fingerprint', $deviceFingerprint)
                ->where('is_trusted', true)
                ->first();

            if ($trustedDevice) {
                // Device is trusted, update last_used_at
                $trustedDevice->update(['last_used_at' => now()]);
                return redirect()->intended(route('dashboard'))->with('success', 'Logged in successfully!');
            }

            // Device is not trusted, require PIN verification
            return redirect()->route('verify-pin')->with('success', 'Please verify with your PIN to continue.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showSetupPin()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userPin = Auth::user()->userPin;
        if ($userPin && $userPin->is_set) {
            return redirect()->route('dashboard');
        }

        return view('auth.setup-pin');
    }

    public function storeSetupPin(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'pin' => ['required', 'string', 'min:4', 'max:6', 'regex:/^\d+$/'],
            'pin_confirmation' => ['required', 'same:pin'],
        ]);

        $user = Auth::user();
        UserPin::updateOrCreate(
            ['user_id' => $user->id],
            [
                'pin_hash' => Hash::make($validated['pin']),
                'is_set' => true,
                'pin_set_at' => now(),
            ]
        );

        return redirect()->route('dashboard')->with('success', 'PIN set successfully!');
    }

    public function showVerifyPin()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $userPin = $user->userPin;
        if (!$userPin || !$userPin->is_set) {
            return redirect()->route('setup-pin');
        }

        $deviceInfo = DeviceFingerprintService::getDeviceInfo(request());

        return view('auth.verify-pin', [
            'deviceInfo' => $deviceInfo,
        ]);
    }

    public function verifyPin(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'pin' => ['required', 'string', 'regex:/^\d+$/'],
            'trust_device' => ['sometimes', 'boolean'],
        ]);

        $userPin = $user->userPin;

        if (!$userPin || !Hash::check($validated['pin'], $userPin->pin_hash)) {
            return back()->withErrors(['pin' => 'Invalid PIN. Please try again.']);
        }

        // PIN is correct
        if ($request->boolean('trust_device')) {
            $deviceFingerprint = DeviceFingerprintService::generate($request);
            $deviceInfo = DeviceFingerprintService::getDeviceInfo($request);

            TrustedDevice::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'device_fingerprint' => $deviceFingerprint,
                ],
                [
                    'device_name' => $deviceInfo['browser'] . ' on ' . $deviceInfo['os'],
                    'device_type' => $deviceInfo['device_type'],
                    'browser' => $deviceInfo['browser'],
                    'os' => $deviceInfo['os'],
                    'ip_address' => $deviceInfo['ip_address'],
                    'is_trusted' => true,
                    'trusted_at' => now(),
                    'last_used_at' => now(),
                ]
            );

            return redirect()->route('dashboard')->with('success', 'Logged in successfully! Device trusted.');
        }

        return redirect()->route('dashboard')->with('success', 'Logged in successfully!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Logged out successfully!');
    }
}

