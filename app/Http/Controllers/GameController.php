<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class GameController extends Controller
{
    public function newGame(){
        Log::debug("new game");
    }
}
