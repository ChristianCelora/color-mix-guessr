<?php

namespace App\ColorGuessr\ColorConverter;

class ColorConverter{
    /**
     * Converts from rgb array to hex string (without #)
     * @param array $rgb
     * @return string hex string
     */
    public static function rgbToHex(array $rgb): string{
        $hex = "";
        foreach($rgb as $val){
            $hex .= str_pad(dechex($val), 2, "0", STR_PAD_LEFT);
        }
        return $hex;
    }
}