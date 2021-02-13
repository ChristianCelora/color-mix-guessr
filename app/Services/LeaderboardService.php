<?php
namespace App\Services;

use App\Services\DTO\{IDto, LeaderboardDto};
// use App\ColorGuessr\GameGenerator\EasyGame;
use App\Models\{Game, Step, Color};
// use App\ColorGuessr\ColorConverter\ColorConverter;
use Illuminate\Support\Facades\DB;

class LeaderboardService implements IService{
    public static function make(IDto $dto): IService{
        if(!$dto instanceof LeaderboardDto){
            throw new InvalidArgumentException("GameService needs to receive a GameDto.");
        }
        return new LeaderboardService($dto);
    }

    private $from;
    private $to;
    private $leaderboard;

    public function __construct(LeaderboardDto $dto){
        list($this->from, $this->to) = $dto->getRanges();
        $this->leaderboard = $this->makeLeaderboard();
    }
    /**
     * 
     */
    private function makeLeaderboard(int $limit = -1): array{
        $leaderboard = array();

        $query = DB::table("games")
            ->select("games.id", "games.user_id", DB::raw("SUM(steps.score) as game_score"))
            ->join("steps", "steps.game_id", "=", "games.id")
            ->where("ended_at", ">=", $this->from->format("Y-m-d H:i:s"));
        if($this->from){
            $query->where("ended_at", ">=", $this->from->format("Y-m-d H:i:s"));
        } 
        if($this->to){
            $query->where("ended_at", "<=", $this->to->format("Y-m-d H:i:s"));
        }   
        $query->groupBy("games.id")->orderBy("game_score", "desc");
        if($limit > 0){
            $query->limit($limit);
        }
            
        $res = $query->get();
        foreach($res as $game){
            $leaderboard[] = $game;
        }

        return $leaderboard;
    }
    /**
     * Prepare data to display in the view of the game
     * @return array
     */
    public function getData(): array{

    }
}