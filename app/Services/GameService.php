<?php
namespace App\Services;

use App\Services\DTO\{IDto, GameDto};
use App\ColorGuessr\GameGenerator\EasyGame;
use App\Models\{Game, Step, Color};
use App\ColorGuessr\ColorConverter\ColorConverter;

class GameService implements IService{
    const MAX_SCORE = 765; // 255 * 3 
    public static function make(IDto $dto): IService{
        if(!$dto instanceof GameDto){
            throw new InvalidArgumentException("GameService needs to receive a GameDto.");
        }
        return new GameService($dto);
    }

    /** @var App\Models\Game $game */
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
            $in_c = $color->getColorAsArray();
            $in_c["weight"] = $color->pivot->weight * 100;
            $input_colors[] = $in_c;
            unset($in_c);
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
        $data["max_score"] = self::MAX_SCORE;
        $data["steps"] = array();
        $sum_score = 0;
        foreach($this->game->steps as $step){
            $data["steps"][] = $step->getStepResultData();
            $sum_score += end($data["steps"])["score"];
        }
        $data["totals"] = array(
            "score" => $sum_score, 
            "max_score" => self::MAX_SCORE * sizeof($data["steps"])
        );
        return $data;
    }    
    /**
     * Update user guess and score
     * @param array $guess
     * @return void
     */
    public function updateUserGuess(array $guess): void{
        $step_model = $this->getCurrentStepModel();
        $solution = Color::find($step_model->solution);
        $step_model->user_guess = ColorConverter::rgbToHex($guess);
        $step_model->score = $this->calcScore($guess, $solution->getColorAsArray());
        $step_model->save();
    }
    /**
     * Prepare data for game step solution
     * @return array
     */
    public function getSolutionData(): array{
        $res = array();

        $current_step = $this->getCurrentStepModel();
        $solution = Color::find($current_step->solution);
        $res["solution"] = $solution->getColorAsArray();
        $res["solution_label"] = $solution->name;
        $res["score"] = $current_step->score;
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