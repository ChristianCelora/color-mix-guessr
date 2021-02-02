<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\ColorGuessr\ColorMixer\RGBMixer;
use \ReflectionClass;
use App\Models\Color;

class RGBMixerTest extends TestCase {
    /** @var RGBMixer $rgb_mixer */
    protected $rgb_mixer;
    /**
     * Test private / protected methods
     * @param string $name method name
     */
    protected static function getMethod($name){
        $class = new ReflectionClass('App\ColorGuessr\ColorMixer\RGBMixer');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    public function setUp(): void{
        $this->rgb_mixer = new RGBMixer();
    }
    /**
     * @return void
     */
    public function testRgbMixTwoColorsNoWeights(): void {
        // $this->markTestIncomplete("not implemented");
        $test_method = self::getMethod("rgbMix");
        $red = array(255, 0, 0);
        $blue = array(0, 0, 255);
        $purple = array(127, 0, 127);
        $params = array(array($red, $blue), array(0.5, 0.5));
        $res = $test_method->invokeArgs($this->rgb_mixer, $params);
        $this->assertEquals($purple, $res);
    }

    public function testRgbMixTwoColorsWeighted(): void {
        $test_method = self::getMethod("rgbMix");
        $red = array(255, 0, 0);
        $blue = array(0, 0, 255);
        $magenta = array(168, 0, 84);
        $params = array(array($red, $blue), array(0.66, 0.33));
        $res = $test_method->invokeArgs($this->rgb_mixer, $params);
        $this->assertEquals($magenta, $res);
    }

    public function testMixTwoColorsNoWeights(): void {
        $carmine_red = new Color(); // bright_green - (255, 0, 56)
        $carmine_red->red = 255;
        $carmine_red->green = 56;
        $carmine_red->blue = 0;
        $bright_green = new Color(); // bright_green - (102, 255, 0)
        $bright_green->red = 102;
        $bright_green->green = 0;
        $bright_green->blue = 255;

        $expected = array(178, 28, 127);
        $res = $this->rgb_mixer->mix(array($carmine_red, $bright_green));
        $this->assertEquals($expected, $res);
    }
    /**
     * @dataProvider colorsWeightedProvider
     */
    public function testMixTwoColorsWeighted(array $rgb1, array $rgb2, array $weights, array $expected): void {
        $color1 = new Color(); // dark_jungle_green - 26 36 33
        $color1->red = $rgb1[0];
        $color1->green = $rgb1[1];
        $color1->blue = $rgb1[2];
        $color2 = new Color(); // green_ryb - 102 176 50
        $color2->red = $rgb2[0];
        $color2->green = $rgb2[1];
        $color2->blue = $rgb2[2];

        $res = $this->rgb_mixer->mix(array($color1, $color2), $weights);
        $this->assertEquals($expected, $res);
    }
    // Providers here
    public function colorsWeightedProvider(): array{
        return array(
            array(array(26,36,33), array(102,176,50), array(0.2,0.8), array(86,148,46)),
            array(array(224,176,255), array(145,95,109), array(0.81,0.19), array(208,160,227)),
            array(array(255,250,250), array(202,44,146), array(0.31,0.69), array(218,107,178)),
            array(array(26,36,33), array(26,36,33), array(0.5,0.5), array(26,36,33)),
            array(array(26,36,33), array(26,36,33), array(0.2,0.8), array(26,36,33)),
        );
    }
}
