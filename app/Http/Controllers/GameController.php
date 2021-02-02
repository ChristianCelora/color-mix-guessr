<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\ColorGuessr\GameGenerator\GameGeneratorFactory;
use App\Models\{Game, Step, Color};
use \Exception;
use App\Services\GameService;
use App\Services\DTO\GameDto;

use Illuminate\Http\Request;

class GameController extends Controller
{
    const MAX_SCORE = 765; // 255 * 3 
    /**
     * Creates new game
     * @param Request $request 
     */
    public function newGame(Request $request){
        $game_generator = GameGeneratorFactory::create($request->input("difficulty"));
        $session_id = $request->session()->getId();
        if(Auth::check()){
            $game_id = $game_generator->createGame($session_id, Auth::id());
        }else{
            $game_id = $game_generator->createGame($session_id);
        }
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
        $game_service = GameService::make(new GameDto($game_id));
        return view("game", array("data" => $game_service->getGameData()));
    }
    /**
     * Displays game results
     */
    public function displayResults(int $game_id){
        $game_service = GameService::make(new GameDto($game_id));
        $data = $game_service->getResultsGameData();
        return view("results", array("data" => $data));
    }
    /**
     * Return solution data and calculates scores
     * @param Request $request 
     * @return array
     */
    public function setUserGuess(Request $request){
        $res = array();
        $game_service = GameService::make(new GameDto($request->input("game_id")));
        $game_service->updateUserGuess($request->input("guess"));
        $ret = $game_service->getSolutionData();
        return $ret;
    }   
}
