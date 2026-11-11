<?php

namespace Spdotdev\SplotnikovDev;

use Illuminate\Http\Request;
use MaxMind\Db\Reader;
use Throwable;

/**
 * The one-time Dutch offer on English pages that have a Dutch twin: shown to a visitor from a Dutch IP (free DB-IP
 * Lite country file, config geoip_db) or to a visitor whose browser asks for Dutch first. Never a forced redirect
 * (Google, "Localized versions"); never shown again once the visitor chose, the choice is in the cookie scuttle_lang.
 * Mirrors scuttle-dev's LanguageOffer, so both sites behave the same.
 */
final class LanguageOffer
{
    public const COOKIE = 'scuttle_lang';

    private static ?Reader $reader = null;

    private static ?string $readerPath = null;

    public static function shouldOffer(Request $request, bool $paired, bool $isNl, ?string $country = null): bool
    {
        if (! $paired || $isNl) {
            return false;
        }
        if ($request->cookie(self::COOKIE) !== null) {
            return false;
        }
        if ($country === 'NL') {
            return true;
        }
        // nginx passes an empty value when the browser sends no header, so check the value, not only the key.
        if (trim((string) $request->header('Accept-Language', '')) === '') {
            return false;
        }

        return $request->getPreferredLanguage(['nl', 'en']) === 'nl';
    }

    /** ISO country code of the visitor's IP, or null when unknown or no database is configured. */
    public static function countryOf(Request $request): ?string
    {
        $path = (string) config('splotnikov-dev.geoip_db', '');
        if ($path === '' || ! is_file($path)) {
            return null;
        }
        try {
            if (self::$reader === null || self::$readerPath !== $path) {
                self::$reader = new Reader($path);
                self::$readerPath = $path;
            }
            $record = self::$reader->get((string) $request->ip());
        } catch (Throwable) {
            return null;
        }
        $iso = is_array($record) ? ($record['country']['iso_code'] ?? null) : null;

        return is_string($iso) && $iso !== '' ? strtoupper($iso) : null;
    }
}
