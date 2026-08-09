<?php

namespace Mca\AccessIntel\Support;

final class McaAccessIntelLocale
{
    public static function resolve(): string
    {
        $locale = config('access-intel.locale');

        if (is_string($locale) && $locale !== '') {
            return $locale;
        }

        return (string) app()->getLocale();
    }

    public static function apply(): void
    {
        app()->setLocale(self::resolve());
    }
}
