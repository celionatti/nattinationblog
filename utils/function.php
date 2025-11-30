<?php

declare(strict_types=1);

/*
 |----------------------------------------------------------------
 |Root Functions
 |----------------------------------------------------------------
 |
 */

/**
 * Format money value based on currency configuration
 */
function formatMoney($amount, $currency = null)
{
    $currency = $currency ?? config('app.currency', 'NGN');
    $symbol = config('app.currency_symbol', '₦');
    $decimals = config('app.currency_decimals', 2);

    return $symbol . number_format($amount, $decimals);
}

/**
 * Adjust color brightness for charts
 */
function adjustBrightness($hex, $steps)
{
    $steps = max(-255, min(255, $steps));
    $hex = str_replace('#', '', $hex);

    if (strlen($hex) == 3) {
        $hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
    }

    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    $r = max(0, min(255, $r + $steps));
    $g = max(0, min(255, $g + $steps));
    $b = max(0, min(255, $b + $steps));

    $r_hex = str_pad(dechex($r), 2, '0', STR_PAD_LEFT);
    $g_hex = str_pad(dechex($g), 2, '0', STR_PAD_LEFT);
    $b_hex = str_pad(dechex($b), 2, '0', STR_PAD_LEFT);

    return '#' . $r_hex . $g_hex . $b_hex;
}
