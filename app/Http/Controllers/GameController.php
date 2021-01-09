<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use App\ColorGuessr\GameGenerator\EasyGame;
use App\Models\Game;
use App\Models\Step;
use App\Models\Color;
use \Exception;

use Illuminate\Http\Request;

class GameController extends Controller
{
    const MAX_SCORE = 765; // 255 * 3 
    /**
     * Creates new game
     * @param Request $request 
     */
    public function newGame(Request $request){
        $game_generator = new EasyGame(); // Replace with factory
        $game_id = $game_generator->createGame($request->session()->getId());
        return redirect()->route("play", ["game_id" => $game_id]);
    }
    /**
     * Updates game step. Redirects to next step if exists. Redirect to result view otherwise
     * @param Request $request 
     */
    public function nextStep(Request $request){
        $game_id = $request->input("game_id");
        $game_model = Game::find($game_id);
        $next_step = $game_model->current_step + 1;
        // Find next step
        $step_model = Step::where("game_id", $game_id)->where("number", $next_step)->get();
        if($step_model->isEmpty()){
            return redirect()->route("results", ["game_id" => $game_id]);
        }
        $game_model->current_step = $next_step;
        $game_model->save();
        return redirect()->route("play", ["game_id" => $game_id]);
    }
    /**
     * Prepare data to display view of the game
     * @param int $game_id
     */
    public function displayGame(int $game_id){
        $data = $this->prepareGameData($game_id);
        return view("game", array("data" => $data));
    }
    /**
     * Prepare data to display view of the game
     * @param int $game_id
     * @return array
     */
    private function prepareGameData(int $game_id): array{
        $data = array();
        $game_generator = new EasyGame(); // Replace with factory

        $current_step = $this->getCurrentStepModel($game_id);
        $data["game_id"] = $game_id;
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
     * Return solution data and calculates scores
     * @param Request $request 
     * @return array
     */
    public function getSolution(Request $request){
        $res = array();

        $current_step = $this->getCurrentStepModel($request->input("game_id"));
        $solution = Color::find($current_step->solution);
        $res["solution"] = $solution->getColorAsArray();
        $res["solution_label"] = $solution->name;
        $res["score"] = $this->calcScore($request->input("guess"), $solution->getColorAsArray());
        $res["max_score"] = self::MAX_SCORE;

        return $res;
    }
    /**
     * Returns model of current step given game id
     * @param int $game_id
     * @return App\Models\Step|null null if not found
     */
    private function getCurrentStepModel(int $game_id){
        return (Game::find($game_id))->resume_step->first();
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
}
