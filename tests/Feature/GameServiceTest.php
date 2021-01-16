<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\ColorGuessr\GameGenerator\EasyGame;
use App\Services\GameService;
use App\Services\DTO\GameDto;
use App\Models\Game;

class GameServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @var int $test_game_id game id for test */
    private $test_game_id;
    /** @var EasyGame $game_service */
    private $game_service;

    public function setUp(): void{
        parent::setUp();
        $this->seed(\Database\Seeders\ColorTableSeeder::class);
        // Create test game
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
        // $this->markTestIncomplete("test incomplete");
        $this->game_service->updateUserGuess($guess);
        $current_step = Game::find($this->test_game_id)->resume_step->first();
        $this->assertEquals($expected_hex, $current_step->user_guess);
        $this->assertTrue($current_step->score >= 0 && $current_step->score <= GameService::MAX_SCORE);
    }

    public function testGetSolutionData(){
        $this->markTestIncomplete("test incomplete");
    }

    public function testCalcScore(){
        $this->markTestIncomplete("test incomplete");
    }

    public function testGetCurrentStepModel(){
        $this->markTestIncomplete("test incomplete");
    }

    // Providers here
    public function userGuessProvider(): array{
        return array(
            array(array("red" => 75, "green" => 245, "blue" => 66), "4bf542"),
            array(array("red" => 105, "green" => 64, "blue" => 96), "694060"),
            array(array("red" => 0, "green" => 0, "blue" => 0), "000000"),
        );
    }
}
