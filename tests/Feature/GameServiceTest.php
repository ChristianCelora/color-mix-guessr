<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\ColorGuessr\GameGenerator\EasyGame;
use App\Services\GameService;
use App\Services\DTO\GameDto;

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
    }

    public function testGetResultsGameData(){
        $this->markTestIncomplete("test incomplete");
    }

    public function testUpdateUserGuess(){
        $this->markTestIncomplete("test incomplete");
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
}
