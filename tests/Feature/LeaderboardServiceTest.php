<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use \ReflectionClass;
use \DateTime;

use App\Services\LeaderboardService;
use App\Services\DTO\LeaderboardDto;
use App\ColorGuessr\GameGenerator\GameGeneratorFactory;
use App\Models\{Game, User};

class LeaderboardServiceTest extends TestCase {
    use RefreshDatabase;

    const SESSION_TEST_PREFIX = "test_";
    /**
     * Test private / protected methods
     * @param string $name method name
     */
    protected static function getMethod($name){
        $class = new ReflectionClass("App\Services\LeaderboardService");
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    public function setUp(): void{
        parent::setUp();
        $this->seed(\Database\Seeders\ColorTableSeeder::class);
    }

    public function testMakeMethod(): LeaderboardService{
        $from = new DateTime();
        $to = new DateTime();
        $leaderboard_service = LeaderboardService::make(new LeaderboardDto($from, $to));
        $this->assertInstanceOf(LeaderboardService::class, $leaderboard_service);
        return $leaderboard_service;
    }
    /**
     * @depends testMakeMethod
     */
    public function testMakeLeaderboardNoGames(LeaderboardService $service){
        $test_method = self::getMethod("makeLeaderboard");
        $leaderboard = $test_method->invokeArgs($service, array());
        $this->assertIsArray($leaderboard);
        $this->assertEmpty($leaderboard);
    }
    /**
     * @depends testMakeMethod
     */
    public function testMakeLeaderboardWithGames(LeaderboardService $service){
        $n_games = 2;
        $users = User::factory()->count(2)->create();
        $scores = [[10,10], [20,20]];
        $ids = $this->createTestGames($n_games, $users->all(), $scores);

        $test_method = self::getMethod("makeLeaderboard");
        $leaderboard = $test_method->invokeArgs($service, array());
        $this->assertIsArray($leaderboard);
        $this->assertCount($n_games, $leaderboard);
        foreach($leaderboard as $game){
            $this->assertObjectHasAttribute("id", $game);
            $this->assertObjectHasAttribute("user_id", $game);
            $this->assertObjectHasAttribute("game_score", $game);
        }
        $this->assertEquals($ids[1], $leaderboard[0]->id);
        $this->assertEquals($ids[0], $leaderboard[1]->id);
        $this->assertEquals($users[0]->id, $leaderboard[1]->user_id);
        $this->assertEquals($users[1]->id, $leaderboard[0]->user_id);
        $this->assertEquals(array_sum($scores[1]), $leaderboard[0]->game_score);
        $this->assertEquals(array_sum($scores[0]), $leaderboard[1]->game_score);
    }
    /**
     * @depends testMakeMethod
     */
    public function testMakeLeaderboardWithLimitedGames(LeaderboardService $service){
        $n_games = 5;
        $users = User::factory()->count(5)->create();
        $scores = [[10,10], [20,20], [10,10], [10,10], [10,10]];
        $ids = $this->createTestGames($n_games, $users->all(), $scores);

        $test_method = self::getMethod("makeLeaderboard");
        $leaderboard = $test_method->invokeArgs($service, array(2));
        $this->assertCount(2, $leaderboard);
        $leaderboard = $test_method->invokeArgs($service, array(1));
        $this->assertCount(1, $leaderboard);
        $leaderboard = $test_method->invokeArgs($service, array());
        $this->assertCount($n_games, $leaderboard);
    }

    private function createTestGames(int $n_games, $users, array $scores): array{
        $game_ids = array();
        $game_gen = GameGeneratorFactory::create(GameGeneratorFactory::EASY_DIFFICULTY);
        for($i = 0; $i < $n_games; $i++){
            $game_id = $game_gen->createGame(self::SESSION_TEST_PREFIX.$i, ($users[$i])->id);
            $game_ids[] = $game_id;
            $game_model = Game::find($game_id);
            $j = 0;
            foreach($game_model->steps as $step){
                $step->score = (isset($scores[$i][$j])) ? $scores[$i][$j] : 0;
                $step->save();
                $j++;
            }
        }
        return $game_ids;
    }
}
