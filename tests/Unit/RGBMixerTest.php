<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\ColorGuessr\ColorMixer\RGBMixer;
use \ReflectionClass;

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
    public function testRgbMixNoWeights(): void {
        // $this->markTestIncomplete("not implemented");
        $test_method = self::getMethod("rgbMix");
        $red = array(255, 0, 0);
        $blue = array(0, 0, 255);
        $purple = array(127, 0, 127);
        $params = array(array($red, $blue), array(0.5, 0.5));
        $res = $test_method->invokeArgs($this->rgb_mixer, $params);
        $this->assertEquals($purple, $res);
    }

    public function testRgbMixWeighted(): void {
        $test_method = self::getMethod("rgbMix");
        $red = array(255, 0, 0);
        $blue = array(0, 0, 255);
        $magenta = array(168, 0, 84);
        $params = array(array($red, $blue), array(0.66, 0.33));
        $res = $test_method->invokeArgs($this->rgb_mixer, $params);
        $this->assertEquals($magenta, $res);
    }
}
