<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use App\ColorGuessr\GameGenerator\EasyGame;
use Tests\TestCase;
use \ReflectionClass;

class GameGeneratorTest extends TestCase{
    use RefreshDatabase;
    /** @override */
    public function setUp(): void{
        parent::setUp();
        // Apply seed after refresh database
        $this->seed(\Database\Seeders\ColorTableSeeder::class);
    }
    /**
     * Test private / protected methods
     * @param string $name method name
     */
    protected static function getMethod($name){
        $class = new ReflectionClass('App\ColorGuessr\GameGenerator\EasyGame');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
    /**
     * @dataProvider colorProvider
     */
    public function testGetClosestColor(string $color_code, array $rgb): void{
        $game_generator = new EasyGame();
        $test_method = self::getMethod("getClosestColor");

        $res = $test_method->invokeArgs($game_generator, array($rgb));
        $this->assertObjectHasAttribute("color_code", $res);
        $this->assertEquals($res->color_code, $color_code);
    }

    public function testCreateStep(): void{
        $this->markTestIncomplete("incomplete test");
    }

    public function testCreateGame(): void{
        $this->markTestIncomplete("incomplete test");
    }

    // Providers here
    public function colorProvider(): array{
        return array(
            array("fern_green", array(79, 121, 66)),
            array("fern_green", array(75, 115, 60)),
            array("amber", array(255, 193, 5)),
            array("amethyst", array(150, 105, 200))
        );
    }
}
