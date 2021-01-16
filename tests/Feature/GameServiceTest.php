<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\ColorGuessr\GameGenerator\EasyGame;
use App\Services\GameService;
use App\Services\DTO\GameDto;
use App\Models\{Game, Color};
use \ReflectionClass;

class GameServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @var int $test_game_id game id for test */
    private $test_game_id;
    /** @var EasyGame $game_service */
    private $game_service;
    /**
     * Test private / protected methods
     * @param string $name method name
     */
    protected static function getMethod($name){
        $class = new ReflectionClass('App\Services\GameService');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    public function setUp(): void{
        parent::setUp();
        $this->seed(\Database\Seeders\ColorTableSeeder::class);
        // Create test game (need to be created before every test cause refresh database is on)
        $game_generator = new EasyGame();
        $this->test_game_id = $game_generator->createGame("fake_session_id");
        $this->game_service = GameService::make(new GameDto($this->test_game_id));
    }

    public function testGetGameData(){
        $actual_data = $this->game_service->getGameData();
        $this->assertEquals($this->test_game_id, $actual_data["game_id"]);
        $this->assertEquals(1, $actual_data["step_number"]); // First step
        $this->assertIsArray($actual_data["input_colors"]);
        $this->assertNotEmpty($actual_data["input_colors"]);
        $this->assertIsArray($actual_data["solution"]);
        $this->assertEquals(EasyGame::SECONDS_TO_ANSWER, $actual_data["seconds"]);
    }

    public function testGetResultsGameData(){
        $this->markTestIncomplete("test incomplete");
    }
    /**
     * @dataProvider userGuessProvider
     */
    public function testUpdateUserGuess(array $guess, string $expected_hex){
        $this->game_service->updateUserGuess($guess);
        $current_step = Game::find($this->test_game_id)->resume_step->first();
        $this->assertEquals($expected_hex, $current_step->user_guess);
        $this->assertTrue($current_step->score >= 0 && $current_step->score <= GameService::MAX_SCORE);
    }
    /**
     * @dataProvider userGuessProvider
     */
    public function testGetSolutionData(array $guess, string $expected_hex){
        $current_step = Game::find($this->test_game_id)->resume_step->first();
        $sol_data = $this->game_service->getSolutionData();
        $solution = Color::find($current_step->solution);
        $this->assertIsArray($sol_data["solution"]);
        $this->assertTrue($sol_data["max_score"] >= 0 && $sol_data["max_score"] <= GameService::MAX_SCORE);
        $this->assertEquals(GameService::MAX_SCORE, $sol_data["max_score"]);
    }
    /**
     * @dataProvider calcScoreProvider
     */
    public function testCalcScore(array $guess, array $solution, int $score){
        $test_method = self::getMethod("calcScore");
        $params = array($guess, $solution);
        $actual_score = $test_method->invokeArgs($this->game_service, $params);
        $this->assertEquals($score, $actual_score);
    }

    public function testGetCurrentStepModel(){
        $test_method = self::getMethod("getCurrentStepModel");
        $current_step = $test_method->invokeArgs($this->game_service, array());
        $this->assertInstanceOf("App\Models\Step", $current_step);
        $this->assertEquals(1, $current_step->number); // Must be first step
    }

    // Providers here
    public function userGuessProvider(): array{
        return array(
            array(array("red" => 75, "green" => 245, "blue" => 66), "4bf542"),
            array(array("red" => 105, "green" => 64, "blue" => 96), "694060"),
            array(array("red" => 0, "green" => 0, "blue" => 0), "000000"),
        );
    }

    public function calcScoreProvider(): array{
        return array(
            array(
                array("red" => 75, "green" => 245, "blue" => 66), 
                array("red" => 75, "green" => 245, "blue" => 66), 
                GameService::MAX_SCORE
            ),
            array(
                array("red" => 255, "green" => 255, "blue" => 255), 
                array("red" => 0, "green" => 0, "blue" => 0), 
                0
            ),
            array(
                array("red" => 75, "green" => 245, "blue" => 66), 
                array("red" => 245, "green" => 66, "blue" => 75), 
                GameService::MAX_SCORE - 358
            ),
            array(
                array("red" => 114, "green" => 10, "blue" => 0), 
                array("red" => 210, "green" => 97, "blue" => 158), 
                GameService::MAX_SCORE - 341
            ),
        );
    }
}
