<?php

use Mca\AccessIntel\Services\AccessIntelService;

if (! function_exists('mca_access_intel')) {
    function mca_access_intel(): AccessIntelService
    {
        return app(AccessIntelService::class);
    }
}

if (! function_exists('mca_intel')) {
    /** @param  array<string, string|int>  $replace */
    function mca_intel(string $key, array $replace = []): string
    {
        return (string) __('mca-access-intel::access-intel.'.$key, $replace);
    }
}
