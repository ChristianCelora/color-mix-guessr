<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\ColorGuessr\ColorConverter\ColorConverter;

class ColorConverterTest extends TestCase {
    /**
     * @dataProvider colorProvider
     */
    public function testRgbToHex($hex, $rgb){
        $this->assertEquals($hex, ColorConverter::rgbToHex($rgb));
    }

    // Providers here
    public function colorProvider(): array{
        return array(
            array("376b45", array(55, 107, 69)),
            array("000000", array(0, 0, 0)),
            array("ffffff", array(255, 255, 255)),
            array("e0de38", array(224, 222, 56)),
            array("c4c200", array(196, 194, 0))
        );
    }
}