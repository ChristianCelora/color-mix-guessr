<?php
namespace App\ColorGuessr\GameGenerator;

use App\Models\Game;

class EasyGame extends AbstractGameGenerator {
    const N_COLORS_INPUT = 2;
    const N_STEPS = 3;
    const DIFFICULTY = Game::EASY_DIFFICULTY;
}