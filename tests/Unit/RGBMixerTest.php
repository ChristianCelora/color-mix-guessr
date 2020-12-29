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
        $carmine_red->blue = 0;
        $carmine_red->green = 56;
        $bright_green = new Color(); // bright_green - (102, 255, 0)
        $bright_green->red = 102;
        $bright_green->blue = 255;
        $bright_green->green = 0;

        $expected = array(178, 127, 28);
        $res = $this->rgb_mixer->mix(array($carmine_red, $bright_green));
        $this->assertEquals($expected, $res);
    }

    public function testMixTwoColorsWeighted(): void {
        $dark_jungle_green = new Color(); // dark_jungle_green - 26 36 33
        $dark_jungle_green->red = 26;
        $dark_jungle_green->blue = 36;
        $dark_jungle_green->green = 33;
        $green_ryb = new Color(); // green_ryb - 102 176 50
        $green_ryb->red = 102;
        $green_ryb->blue = 176;
        $green_ryb->green = 50;

        $expected = array(86, 148, 46);
        $res = $this->rgb_mixer->mix(array($dark_jungle_green, $green_ryb), array(0.2, 0.8));
        $this->assertEquals($expected, $res);
    }
}
