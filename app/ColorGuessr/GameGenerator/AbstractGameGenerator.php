<?php
namespace App\ColorGuessr\GameGenerator;
use App\Models\Game;
use App\Models\Step;
use App\Models\Color;
use App\Models\ColorStep;
use App\ColorGuessr\ColorMixer\RGBMixer;
use Illuminate\Support\Facades\DB;

abstract class AbstractGameGenerator {
    /** @var RGBMixer $mixer */
    protected $mixer;

    public function __construct(){
        $this->mixer = new RGBMixer();
    }

    public function createGame(string $session_id){
        $game = new Game();
        $game->session_id = $session_id; // Guest user
        $game->difficulty = static::DIFFICULTY;
        $game->current_step = 1;
        $game->save();
        // Steps generator
        for($i = 0; $i < static::N_STEPS; $i++){
            $this->createStep($game->id, $i+1);
        }
        return $game->id;
    }

    protected function createStep(int $game_id, int $index){
        $step = new Step();
        $step->game_id = $game_id;
        $step->number = $index;
        $step->solution = -1;
        $step->save();
        // Color Picker
        $colors = Color::inRandomOrder()->limit(static::N_COLORS_INPUT)->get();
        foreach($colors as $color){
            $color_step = new ColorStep();
            $color_step->color_id = $color->id;
            $color_step->step_id = $step->id;
            $color_step->save();
            unset($color_step);
        }
        // Calculate & update solution
        $step->solution = $this->getClosestColor($this->mixer->mix($colors));
        $step->save();
    }
    /**
     * Get model color closer to mixed color result
     * @param array $rgb
     * @return App\Models\Color closest color
     */
    protected function getClosestColor(array $rgb){
        list($red, $green, $blue) = $rgb;
        $closest_color = DB::table("colors")
            ->select("color_code", "name", "hex", DB::raw("(ABS(red - $red) + ABS(green - $green) + ABS(blue - $blue)) as delta"))
            ->orderBy("delta")
            ->limit(1)
            ->get();
        return ($closest_color && isset($closest_color[0])) ? $closest_color[0] : null;
    }
}