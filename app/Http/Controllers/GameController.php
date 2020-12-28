<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use App\Models\Game;

use Illuminate\Http\Request;

class GameController extends Controller
{
    public function newGame(){
        Log::debug("new game");
        $game = new Game();
        // $game->session_id = 
        $game->difficulty = Game::EASY_DIFFICULTY;
        $game->current_step = 1;
        $game->save();
    }
}
