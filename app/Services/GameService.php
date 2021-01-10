<?php
namespace App\Services;

use App\Services\DTO\{IDto, GameDto};
use App\ColorGuessr\GameGenerator\EasyGame;
use App\Models\{Game, Step, Color};

class GameService implements IService{
    const MAX_SCORE = 765; // 255 * 3 
    public static function make(IDto $dto): IService{
        if(!$dto instanceof GameDto){
            throw new InvalidArgumentException("GameService needs to receive a GameDto.");
        }
        return new GameService($dto);
    }

    private $game;

    public function __construct(GameDto $dto){
        $this->game = Game::find($dto->getId());
    }
    /**
     * Prepare data to display in the view of the game
     * @return array
     */
    public function getGameData(): array{
        $data = array();
        $game_generator = new EasyGame(); // Replace with factory

        $current_step = $this->getCurrentStepModel();
        $data["game_id"] = $this->game->id;
        $data["step_id"] = $current_step->id;
        $data["step_number"] = $current_step->number;
        $input_colors = array();
        foreach($current_step->colors as $color){
            $input_colors[] = $color->getColorAsArray();
        }
        $data["input_colors"] = $input_colors;
        $data["solution"] = (Color::find($current_step->solution))->getColorAsArray();
        $data["seconds"] = $game_generator->getSeconds();
        
        return $data;
    }
    /**
     * Prepare data to display in the game result page
     * @return array
     */
    public function getResultsGameData(): array{
        $data = array();
        $data["game_id"] = $this->game->id;
        return $data;
    }
    
    /**
     * Prepare data for game step solution
     * @param array $guess User RGB guess
     */
    public function getSolutionData(array $guess): array{
        $res = array();

        $current_step = $this->getCurrentStepModel();
        $solution = Color::find($current_step->solution);
        $res["solution"] = $solution->getColorAsArray();
        $res["solution_label"] = $solution->name;
        $res["score"] = $this->calcScore($guess, $solution->getColorAsArray());
        $res["max_score"] = self::MAX_SCORE;

        return $res;
    }
    /**
     * Calculate score
     * @param array $guess      rgb guess
     * @param array $solution   rgb solution
     * @return int              score 
     */
    private function calcScore(array $guess, array $solution): int{
        $dr = abs($guess["red"] - $solution["red"]);
        $dg = abs($guess["green"] - $solution["green"]);
        $db = abs($guess["blue"] - $solution["blue"]);
        return self::MAX_SCORE - ($dr + $dg + $db);
    } 
    /**
     * Returns model of current step given game id
     * @return App\Models\Step|null null if not found
     */
    private function getCurrentStepModel(){
        return $this->game->resume_step->first();
    }
}