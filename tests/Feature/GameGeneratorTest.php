<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use App\ColorGuessr\GameGenerator\EasyGame;
use App\Models\Game;
use App\Models\Step;
use App\Models\Color;
use Tests\TestCase;
use \ReflectionClass;

class GameGeneratorTest extends TestCase{
    use RefreshDatabase;

    const TEST_SESSION_ID = "test_session_id_123";
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
        $this->assertInstanceOf(Color::class, $res);
        $this->assertEquals($res->color_code, $color_code);
    }

    public function testCreateGame(): void{
        $game_generator = new EasyGame();
        $game_id = $game_generator->createGame(self::TEST_SESSION_ID);
        $this->assertIsInt($game_id);

        $game_model = Game::find($game_id);
        $this->assertEquals(self::TEST_SESSION_ID, $game_model->session_id);
        $this->assertEquals(EasyGame::DIFFICULTY, $game_model->difficulty);
        $this->assertEquals(1, $game_model->current_step);
    }
    /**
     * @depends testCreateGame
     */
    public function testCreateGameSteps(): void{
        $easy_game_mock = $this->getMockBuilder(EasyGame::CLASS)
        ->setMethods(["getClosestColor", "createStep"])
        ->getMock();
        $fake_color = new Color();
        $fake_color->id = 1;
        $easy_game_mock->method("getClosestColor")->will($this->returnValue($fake_color));
        $easy_game_mock->expects($this->exactly(EasyGame::N_STEPS))->method("createStep");
        $easy_game_mock->createGame(self::TEST_SESSION_ID);
    }
    /**
     * @depends testCreateGame
     */
    public function testCreateStep(): void{
        $game_generator = new EasyGame();
        $test_method = self::getMethod("createStep");
        $game_id = $game_generator->createGame(self::TEST_SESSION_ID);
        $step_id = $test_method->invokeArgs($game_generator, array($game_id, 1));

        $step_model = Step::find($step_id);
        $this->assertEquals($game_id, $step_model->game_id);
        $this->assertEquals(1, $step_model->number);
        $this->assertNotNull($step_model->solution);
    }
    /**
     * @dataProvider weightsProvider
     */
    public function testCalculateWeights(int $n, float $min, float $max): void{
        $game_generator = new EasyGame();
        $test_method = self::getMethod("calculateWeights");

        $res = $test_method->invokeArgs($game_generator, array($n, $min, $max));
        $this->assertEquals($n, sizeof($res));
        $sum_w = 0; // Sum weights [0,n-1]
        for($i = 0; $i < sizeof($res)-1; $i++){
            $weight = $res[$i];
            $sum_w += $weight;
            $this->assertThat(
                $weight,
                $this->logicalAnd(
                    $this->greaterThanOrEqual($min),
                    $this->lessThanOrEqual($max)
                )
            );
        }
        // Last weight
        $this->assertThat(
            $weight,
            $this->logicalAnd(
                $this->greaterThanOrEqual($min),
                $this->lessThanOrEqual($sum_w)
            )
        );
    }

    // Providers here
    public function colorProvider(): array{
        return array(
            array("fern_green", array(79, 121, 66)),
            array("fern_green", array(75, 115, 60)),
            array("amber", array(255, 193, 5)),
            array("amethyst", array(150, 105, 200)),
            array("sap_green", array(68, 133, 25)),
            array("dark_olive_green", array(89, 102, 38)),
        );
    }

    public function weightsProvider(): array{
        return array(
            array(2, 0.1, 0.9),
            array(3, 0.1, 0.3),
            array(2, 0.4, 0.6),
        );
    }
}
