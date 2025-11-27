<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DeviceFingerprintService
{
    /**
     * Generate a device fingerprint from request data
     */
    public static function generate(Request $request): string
    {
        $fingerprint = [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'accept_language' => $request->header('Accept-Language'),
        ];

        return Hash::make(json_encode($fingerprint));
    }

    /**
     * Extract device information from request
     */
    public static function getDeviceInfo(Request $request): array
    {
        $userAgent = $request->userAgent();

        return [
            'device_type' => self::detectDeviceType($userAgent),
            'browser' => self::detectBrowser($userAgent),
            'os' => self::detectOS($userAgent),
            'ip_address' => $request->ip(),
        ];
    }

    /**
     * Detect device type from user agent
     */
    private static function detectDeviceType(string $userAgent): string
    {
        if (preg_match('/Mobile|Android|iPhone|iPad|iPod/i', $userAgent)) {
            if (preg_match('/iPad/i', $userAgent)) {
                return 'tablet';
            }
            return 'mobile';
        }
        return 'desktop';
    }

    /**
     * Detect browser from user agent
     */
    private static function detectBrowser(string $userAgent): string
    {
        if (preg_match('/Edge/i', $userAgent)) {
            return 'Edge';
        } elseif (preg_match('/Chrome/i', $userAgent)) {
            return 'Chrome';
        } elseif (preg_match('/Safari/i', $userAgent)) {
            return 'Safari';
        } elseif (preg_match('/Firefox/i', $userAgent)) {
            return 'Firefox';
        }
        return 'Unknown';
    }

    /**
     * Detect OS from user agent
     */
    private static function detectOS(string $userAgent): string
    {
        if (preg_match('/Windows/i', $userAgent)) {
            return 'Windows';
        } elseif (preg_match('/Mac/i', $userAgent)) {
            return 'macOS';
        } elseif (preg_match('/Linux/i', $userAgent)) {
            return 'Linux';
        } elseif (preg_match('/iPhone|iPad|iPod/i', $userAgent)) {
            return 'iOS';
        } elseif (preg_match('/Android/i', $userAgent)) {
            return 'Android';
        }
        return 'Unknown';
    }
}
