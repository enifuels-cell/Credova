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

    /**
     * Handle email/password login (for initial setup or first login)
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, remember: false)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $userPin = $user->userPin;

            // If user has PIN set, redirect to PIN-only login with email in session
            if ($userPin && $userPin->is_set) {
                session(['login_email' => $user->email]);
                Auth::logout();
                return redirect()->route('pin-login')->with('success', 'Please enter your PIN to continue.');
            }

            // User doesn't have PIN set, go directly to dashboard
            return redirect()->intended(route('dashboard'))->with('success', 'Logged in successfully!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show PIN verification page (from email/password login on new device)
     */
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

    /**
     * Verify PIN from email/password login on new device
     */
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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Logged out successfully!');
    }

    /**
     * Show PIN-only login page
     */
    public function showPinLogin()
    {
        $email = session('login_email');
        if (!$email) {
            return redirect()->route('login');
        }

        return view('auth.pin-login', ['email' => $email]);
    }

    /**
     * Handle PIN-only login
     */
    public function pinLogin(Request $request)
    {
        $email = session('login_email');

        if (!$email) {
            return redirect()->route('login')->withErrors(['pin' => 'Session expired. Please login again.']);
        }

        $validated = $request->validate([
            'pin' => ['required', 'string', 'regex:/^\d+$/', 'min:4', 'max:6'],
        ]);

        // Find user by email
        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        $userPin = $user->userPin;

        if (!$userPin || !$userPin->is_set) {
            return back()->withErrors(['pin' => 'This account does not have a PIN set.']);
        }

        if (!Hash::check($validated['pin'], $userPin->pin_hash)) {
            return back()->withErrors(['pin' => 'Invalid PIN. Please try again.']);
        }

        // PIN is correct, authenticate user
        Auth::login($user, remember: false);
        $request->session()->regenerate();
        session()->forget('login_email');

        // Check if should trust this device
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

    /**
     * Show settings page
     */
    public function showSettings()
    {
        $user = Auth::user();
        $userPin = $user->userPin;

        return view('settings', [
            'userPin' => $userPin,
        ]);
    }

    /**
     * Update PIN in settings
     */
    public function updatePin(Request $request)
    {
        $user = Auth::user();

        // Verify password first
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'pin' => ['required', 'string', 'min:4', 'max:6', 'regex:/^\d+$/'],
            'pin_confirmation' => ['required', 'same:pin'],
        ]);

        // Check if password is correct
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'The password is incorrect.']);
        }

        // Update or create PIN
        UserPin::updateOrCreate(
            ['user_id' => $user->id],
            [
                'pin_hash' => Hash::make($validated['pin']),
                'is_set' => true,
                'pin_set_at' => now(),
            ]
        );

        return back()->with('success', 'PIN has been successfully updated!');
    }
}
