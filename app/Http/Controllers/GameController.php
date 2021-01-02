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
    public function newGame(Request $request){
        Log::debug("new game");
        $game_generator = new EasyGame(); // Replace with factory
        $game_id = $game_generator->createGame($request->session()->getId());
        // return view("game", array("data" => $data));
        return redirect()->route("play", ["game_id" => $game_id]);
    }

    public function displayGame(int $game_id){
        $data = $this->prepareGameData($game_id);
        return view("game", array("data" => $data));
    }

    private function prepareGameData(int $game_id): array{
        $data = array();
        $game_generator = new EasyGame(); // Replace with factory

        $current_step = (Game::find($game_id))->resume_step->first();
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
}
