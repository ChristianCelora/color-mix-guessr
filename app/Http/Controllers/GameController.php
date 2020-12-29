<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use App\ColorGuessr\GameGenerator\EasyGame;

use Illuminate\Http\Request;

class GameController extends Controller
{
    public function newGame(Request $request){
        Log::debug("new game");
        $game_generator = new EasyGame(); // Replace with factory
        $game = $game_generator->createGame($request->session()->getId());
        dd($game);
    }
}
