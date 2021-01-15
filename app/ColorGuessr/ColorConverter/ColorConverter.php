<?php

namespace App\ColorGuessr\ColorConverter;

class ColorConverter{
    /**
     * Converts from rgb array to hex string
     * @param array $rgb
     * @return string hex string (without #)
     */
    public static function rgbToHex(array $rgb): string{
        $hex = "";
        foreach($rgb as $val){
            $hex .= str_pad(dechex($val), 2, "0", STR_PAD_LEFT);
        }
        return $hex;
    }
    /**
     * Converts from hex string to rgb array
     * @param string $hex
     * @return array $rgb
     */
    public static function hexToRgb(string $hex): array{
        $hex = str_replace("#", "", $hex);
        $rgb = array(
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        );
        return $rgb;
    }
}