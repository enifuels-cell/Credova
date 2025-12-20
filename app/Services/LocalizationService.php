<?php

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class LocalizationService
{
    protected $supportedLocales = [
        'en' => ['name' => 'English', 'native' => 'English', 'flag' => '🇺🇸', 'rtl' => false],
        'es' => ['name' => 'Spanish', 'native' => 'Español', 'flag' => '🇪🇸', 'rtl' => false],
        'fr' => ['name' => 'French', 'native' => 'Français', 'flag' => '🇫🇷', 'rtl' => false],
        'de' => ['name' => 'German', 'native' => 'Deutsch', 'flag' => '🇩🇪', 'rtl' => false],
        'it' => ['name' => 'Italian', 'native' => 'Italiano', 'flag' => '🇮🇹', 'rtl' => false],
        'pt' => ['name' => 'Portuguese', 'native' => 'Português', 'flag' => '🇵🇹', 'rtl' => false],
        'ru' => ['name' => 'Russian', 'native' => 'Русский', 'flag' => '🇷🇺', 'rtl' => false],
        'zh' => ['name' => 'Chinese', 'native' => '中文', 'flag' => '🇨🇳', 'rtl' => false],
        'ja' => ['name' => 'Japanese', 'native' => '日本語', 'flag' => '🇯🇵', 'rtl' => false],
        'ko' => ['name' => 'Korean', 'native' => '한국어', 'flag' => '🇰🇷', 'rtl' => false],
        'ar' => ['name' => 'Arabic', 'native' => 'العربية', 'flag' => '🇸🇦', 'rtl' => true],
        'hi' => ['name' => 'Hindi', 'native' => 'हिन्दी', 'flag' => '🇮🇳', 'rtl' => false],
        'nl' => ['name' => 'Dutch', 'native' => 'Nederlands', 'flag' => '🇳🇱', 'rtl' => false],
        'sv' => ['name' => 'Swedish', 'native' => 'Svenska', 'flag' => '🇸🇪', 'rtl' => false],
        'da' => ['name' => 'Danish', 'native' => 'Dansk', 'flag' => '🇩🇰', 'rtl' => false],
        'no' => ['name' => 'Norwegian', 'native' => 'Norsk', 'flag' => '🇳🇴', 'rtl' => false],
        'fi' => ['name' => 'Finnish', 'native' => 'Suomi', 'flag' => '🇫🇮', 'rtl' => false],
        'pl' => ['name' => 'Polish', 'native' => 'Polski', 'flag' => '🇵🇱', 'rtl' => false],
        'tr' => ['name' => 'Turkish', 'native' => 'Türkçe', 'flag' => '🇹🇷', 'rtl' => false],
        'th' => ['name' => 'Thai', 'native' => 'ไทย', 'flag' => '🇹🇭', 'rtl' => false],
    ];

    protected $defaultLocale = 'en';
    protected $fallbackLocale = 'en';

    /**
     * Get all supported locales
     */
    public function getSupportedLocales(): array
    {
        return $this->supportedLocales;
    }

    /**
     * Set the application locale
     */
    public function setLocale(string $locale): bool
    {
        if (!$this->isSupported($locale)) {
            return false;
        }

        App::setLocale($locale);
        session(['locale' => $locale]);
        
        // Update user preference if logged in
        if (auth()->check()) {
            auth()->user()->update(['preferred_locale' => $locale]);
        }

        return true;
    }

    /**
     * Get current locale
     */
    public function getCurrentLocale(): string
    {
        return App::getLocale();
    }

    /**
     * Detect user's preferred locale
     */
    public function detectLocale($request): string
    {
        // 1. Check if user is logged in and has a preference
        if (auth()->check() && auth()->user()->preferred_locale) {
            return auth()->user()->preferred_locale;
        }

        // 2. Check session
        if (session('locale')) {
            return session('locale');
        }

        // 3. Check Accept-Language header
        $acceptLanguage = $request->server('HTTP_ACCEPT_LANGUAGE');
        if ($acceptLanguage) {
            $preferredLocale = $this->parseAcceptLanguage($acceptLanguage);
            if ($this->isSupported($preferredLocale)) {
                return $preferredLocale;
            }
        }

        // 4. Check GeoIP
        $geoLocale = $this->detectLocaleFromIP($request->ip());
        if ($geoLocale && $this->isSupported($geoLocale)) {
            return $geoLocale;
        }

        return $this->defaultLocale;
    }

    /**
     * Check if locale is supported
     */
    public function isSupported(string $locale): bool
    {
        return array_key_exists($locale, $this->supportedLocales);
    }

    /**
     * Check if locale is RTL
     */
    public function isRTL(string $locale = null): bool
    {
        $locale = $locale ?? $this->getCurrentLocale();
        return $this->supportedLocales[$locale]['rtl'] ?? false;
    }

    /**
     * Get locale information
     */
    public function getLocaleInfo(string $locale): ?array
    {
        return $this->supportedLocales[$locale] ?? null;
    }

    /**
     * Get currency for locale
     */
    public function getCurrencyForLocale(string $locale): array
    {
        $currencyMap = [
            'en' => ['code' => 'USD', 'symbol' => '$', 'name' => 'US Dollar'],
            'es' => ['code' => 'EUR', 'symbol' => '€', 'name' => 'Euro'],
            'fr' => ['code' => 'EUR', 'symbol' => '€', 'name' => 'Euro'],
            'de' => ['code' => 'EUR', 'symbol' => '€', 'name' => 'Euro'],
            'it' => ['code' => 'EUR', 'symbol' => '€', 'name' => 'Euro'],
            'pt' => ['code' => 'EUR', 'symbol' => '€', 'name' => 'Euro'],
            'ru' => ['code' => 'RUB', 'symbol' => '₽', 'name' => 'Russian Ruble'],
            'zh' => ['code' => 'CNY', 'symbol' => '¥', 'name' => 'Chinese Yuan'],
            'ja' => ['code' => 'JPY', 'symbol' => '¥', 'name' => 'Japanese Yen'],
            'ko' => ['code' => 'KRW', 'symbol' => '₩', 'name' => 'Korean Won'],
            'ar' => ['code' => 'SAR', 'symbol' => 'ر.س', 'name' => 'Saudi Riyal'],
            'hi' => ['code' => 'INR', 'symbol' => '₹', 'name' => 'Indian Rupee'],
            'nl' => ['code' => 'EUR', 'symbol' => '€', 'name' => 'Euro'],
            'sv' => ['code' => 'SEK', 'symbol' => 'kr', 'name' => 'Swedish Krona'],
            'da' => ['code' => 'DKK', 'symbol' => 'kr', 'name' => 'Danish Krone'],
            'no' => ['code' => 'NOK', 'symbol' => 'kr', 'name' => 'Norwegian Krone'],
            'fi' => ['code' => 'EUR', 'symbol' => '€', 'name' => 'Euro'],
            'pl' => ['code' => 'PLN', 'symbol' => 'zł', 'name' => 'Polish Złoty'],
            'tr' => ['code' => 'TRY', 'symbol' => '₺', 'name' => 'Turkish Lira'],
            'th' => ['code' => 'THB', 'symbol' => '฿', 'name' => 'Thai Baht'],
        ];

        return $currencyMap[$locale] ?? $currencyMap['en'];
    }

    /**
     * Format currency according to locale
     */
    public function formatCurrency(float $amount, string $locale = null): string
    {
        $locale = $locale ?? $this->getCurrentLocale();
        $currency = $this->getCurrencyForLocale($locale);
        
        $formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($amount, $currency['code']);
    }

    /**
     * Format date according to locale
     */
    public function formatDate(\DateTime $date, string $format = 'medium', string $locale = null): string
    {
        $locale = $locale ?? $this->getCurrentLocale();
        
        $formatter = new \IntlDateFormatter(
            $locale,
            $this->getDateFormatConstant($format),
            \IntlDateFormatter::NONE
        );
        
        return $formatter->format($date);
    }

    /**
     * Format number according to locale
     */
    public function formatNumber(float $number, string $locale = null): string
    {
        $locale = $locale ?? $this->getCurrentLocale();
        $formatter = new \NumberFormatter($locale, \NumberFormatter::DECIMAL);
        return $formatter->format($number);
    }

    /**
     * Translate with fallback
     */
    public function translate(string $key, array $replace = [], string $locale = null): string
    {
        $locale = $locale ?? $this->getCurrentLocale();
        
        // Try current locale
        $translation = trans($key, $replace, $locale);
        
        // If not found and not in fallback locale, try fallback
        if ($translation === $key && $locale !== $this->fallbackLocale) {
            $translation = trans($key, $replace, $this->fallbackLocale);
        }
        
        return $translation;
    }

    /**
     * Get translation with pluralization
     */
    public function transChoice(string $key, int $number, array $replace = [], string $locale = null): string
    {
        $locale = $locale ?? $this->getCurrentLocale();
        
        $translation = trans_choice($key, $number, $replace, $locale);
        
        if ($translation === $key && $locale !== $this->fallbackLocale) {
            $translation = trans_choice($key, $number, $replace, $this->fallbackLocale);
        }
        
        return $translation;
    }

    /**
     * Get missing translations
     */
    public function getMissingTranslations(string $locale = null): array
    {
        $locale = $locale ?? $this->getCurrentLocale();
        $cacheKey = "missing_translations_{$locale}";
        
        return Cache::remember($cacheKey, 3600, function () use ($locale) {
            $basePath = resource_path("lang/{$this->fallbackLocale}");
            $localePath = resource_path("lang/{$locale}");
            
            if (!File::exists($localePath)) {
                return [];
            }
            
            $missing = [];
            $baseFiles = File::allFiles($basePath);
            
            foreach ($baseFiles as $file) {
                $relativePath = $file->getRelativePathname();
                $localeFile = $localePath . '/' . $relativePath;
                
                if (!File::exists($localeFile)) {
                    $missing[] = $relativePath;
                    continue;
                }
                
                $baseKeys = $this->getTranslationKeys(include $file->getPathname());
                $localeKeys = $this->getTranslationKeys(include $localeFile);
                
                $missingKeys = array_diff($baseKeys, $localeKeys);
                if (!empty($missingKeys)) {
                    $missing[$relativePath] = $missingKeys;
                }
            }
            
            return $missing;
        });
    }

    /**
     * Auto-translate missing keys using translation service
     */
    public function autoTranslate(string $text, string $targetLocale, string $sourceLocale = 'en'): string
    {
        // Integration with translation services like Google Translate, DeepL, etc.
        // This is a placeholder implementation
        
        // Use SHA-256 instead of MD5 for cache key generation
        $cacheKey = "auto_translate_" . hash('sha256', $text . $sourceLocale . $targetLocale);
        
        return Cache::remember($cacheKey, 86400, function () use ($text, $targetLocale, $sourceLocale) {
            // In production, integrate with translation APIs
            // For now, return the original text
            return $text;
        });
    }

    /**
     * Generate translation file for a locale
     */
    public function generateTranslationFile(string $locale, array $translations): bool
    {
        $localePath = resource_path("lang/{$locale}");
        
        if (!File::exists($localePath)) {
            File::makeDirectory($localePath, 0755, true);
        }
        
        foreach ($translations as $file => $content) {
            $filePath = $localePath . '/' . $file . '.php';
            $phpContent = "<?php\n\nreturn " . var_export($content, true) . ";\n";
            
            File::put($filePath, $phpContent);
        }
        
        return true;
    }

    /**
     * Get locale-specific configuration
     */
    public function getLocaleConfig(string $locale = null): array
    {
        $locale = $locale ?? $this->getCurrentLocale();
        
        return [
            'locale' => $locale,
            'direction' => $this->isRTL($locale) ? 'rtl' : 'ltr',
            'currency' => $this->getCurrencyForLocale($locale),
            'date_format' => $this->getDateFormat($locale),
            'time_format' => $this->getTimeFormat($locale),
            'number_format' => $this->getNumberFormat($locale),
            'first_day_of_week' => $this->getFirstDayOfWeek($locale),
        ];
    }

    /**
     * Helper methods
     */
    private function parseAcceptLanguage(string $acceptLanguage): string
    {
        $languages = [];
        
        foreach (explode(',', $acceptLanguage) as $lang) {
            $parts = explode(';', trim($lang));
            $language = trim($parts[0]);
            $quality = 1.0;
            
            if (isset($parts[1]) && strpos($parts[1], 'q=') === 0) {
                $quality = floatval(substr($parts[1], 2));
            }
            
            $languages[$language] = $quality;
        }
        
        arsort($languages);
        
        foreach (array_keys($languages) as $language) {
            $locale = strtolower(substr($language, 0, 2));
            if ($this->isSupported($locale)) {
                return $locale;
            }
        }
        
        return $this->defaultLocale;
    }

    private function detectLocaleFromIP(string $ip): ?string
    {
        // Placeholder for GeoIP integration
        // In production, use services like MaxMind GeoIP2
        $countryToLocale = [
            'US' => 'en', 'GB' => 'en', 'AU' => 'en', 'CA' => 'en',
            'ES' => 'es', 'MX' => 'es', 'AR' => 'es', 'CO' => 'es',
            'FR' => 'fr', 'BE' => 'fr', 'CH' => 'fr',
            'DE' => 'de', 'AT' => 'de',
            'IT' => 'it',
            'PT' => 'pt', 'BR' => 'pt',
            'RU' => 'ru',
            'CN' => 'zh', 'TW' => 'zh', 'HK' => 'zh',
            'JP' => 'ja',
            'KR' => 'ko',
            'SA' => 'ar', 'AE' => 'ar', 'EG' => 'ar',
            'IN' => 'hi',
            'NL' => 'nl',
            'SE' => 'sv',
            'DK' => 'da',
            'NO' => 'no',
            'FI' => 'fi',
            'PL' => 'pl',
            'TR' => 'tr',
            'TH' => 'th',
        ];
        
        // This would normally use actual GeoIP lookup
        return null;
    }

    private function getDateFormatConstant(string $format): int
    {
        return match($format) {
            'short' => \IntlDateFormatter::SHORT,
            'medium' => \IntlDateFormatter::MEDIUM,
            'long' => \IntlDateFormatter::LONG,
            'full' => \IntlDateFormatter::FULL,
            default => \IntlDateFormatter::MEDIUM
        };
    }

    private function getTranslationKeys(array $array, string $prefix = ''): array
    {
        $keys = [];
        
        foreach ($array as $key => $value) {
            $currentKey = $prefix ? $prefix . '.' . $key : $key;
            
            if (is_array($value)) {
                $keys = array_merge($keys, $this->getTranslationKeys($value, $currentKey));
            } else {
                $keys[] = $currentKey;
            }
        }
        
        return $keys;
    }

    private function getDateFormat(string $locale): string
    {
        $formats = [
            'en' => 'M j, Y',
            'es' => 'd/m/Y',
            'fr' => 'd/m/Y',
            'de' => 'd.m.Y',
            'it' => 'd/m/Y',
            'pt' => 'd/m/Y',
            'ru' => 'd.m.Y',
            'zh' => 'Y年m月d日',
            'ja' => 'Y年m月d日',
            'ko' => 'Y년 m월 d일',
            'ar' => 'd/m/Y',
            'hi' => 'd/m/Y',
            'nl' => 'd-m-Y',
            'sv' => 'Y-m-d',
            'da' => 'd/m/Y',
            'no' => 'd.m.Y',
            'fi' => 'd.m.Y',
            'pl' => 'd.m.Y',
            'tr' => 'd.m.Y',
            'th' => 'd/m/Y',
        ];
        
        return $formats[$locale] ?? $formats['en'];
    }

    private function getTimeFormat(string $locale): string
    {
        $formats = [
            'en' => 'g:i A',
            'es' => 'H:i',
            'fr' => 'H:i',
            'de' => 'H:i',
            'it' => 'H:i',
            'pt' => 'H:i',
            'ru' => 'H:i',
            'zh' => 'H:i',
            'ja' => 'H:i',
            'ko' => 'H:i',
            'ar' => 'H:i',
            'hi' => 'H:i',
            'nl' => 'H:i',
            'sv' => 'H:i',
            'da' => 'H:i',
            'no' => 'H:i',
            'fi' => 'H:i',
            'pl' => 'H:i',
            'tr' => 'H:i',
            'th' => 'H:i',
        ];
        
        return $formats[$locale] ?? $formats['en'];
    }

    private function getNumberFormat(string $locale): array
    {
        $formats = [
            'en' => ['decimal' => '.', 'thousands' => ','],
            'es' => ['decimal' => ',', 'thousands' => '.'],
            'fr' => ['decimal' => ',', 'thousands' => ' '],
            'de' => ['decimal' => ',', 'thousands' => '.'],
            'it' => ['decimal' => ',', 'thousands' => '.'],
            'pt' => ['decimal' => ',', 'thousands' => '.'],
            'ru' => ['decimal' => ',', 'thousands' => ' '],
            'zh' => ['decimal' => '.', 'thousands' => ','],
            'ja' => ['decimal' => '.', 'thousands' => ','],
            'ko' => ['decimal' => '.', 'thousands' => ','],
            'ar' => ['decimal' => '.', 'thousands' => ','],
            'hi' => ['decimal' => '.', 'thousands' => ','],
            'nl' => ['decimal' => ',', 'thousands' => '.'],
            'sv' => ['decimal' => ',', 'thousands' => ' '],
            'da' => ['decimal' => ',', 'thousands' => '.'],
            'no' => ['decimal' => ',', 'thousands' => ' '],
            'fi' => ['decimal' => ',', 'thousands' => ' '],
            'pl' => ['decimal' => ',', 'thousands' => ' '],
            'tr' => ['decimal' => ',', 'thousands' => '.'],
            'th' => ['decimal' => '.', 'thousands' => ','],
        ];
        
        return $formats[$locale] ?? $formats['en'];
    }

    private function getFirstDayOfWeek(string $locale): int
    {
        // 0 = Sunday, 1 = Monday
        $firstDays = [
            'en' => 0, 'es' => 1, 'fr' => 1, 'de' => 1, 'it' => 1,
            'pt' => 1, 'ru' => 1, 'zh' => 1, 'ja' => 0, 'ko' => 0,
            'ar' => 6, 'hi' => 0, 'nl' => 1, 'sv' => 1, 'da' => 1,
            'no' => 1, 'fi' => 1, 'pl' => 1, 'tr' => 1, 'th' => 0,
        ];
        
        return $firstDays[$locale] ?? 0;
    }
}
