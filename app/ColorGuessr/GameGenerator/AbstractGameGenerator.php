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

    protected function createStep(int $game_id, int $index): int{
        $step = new Step();
        $step->game_id = $game_id;
        $step->number = $index;
        $step->solution = null;
        $step->save();
        // Color Picker
        $colors = Color::inRandomOrder()->limit(static::N_COLORS_INPUT)->get();
        foreach($colors as $color){
            $color_step = new ColorStep();
            $color_step->color_id = $color->id;
            $color_step->step_id = $step->id;
            $color_step->weight = round(1/static::N_COLORS_INPUT, 2);
            $color_step->save();
            unset($color_step);
        }
        // Calculate & update solution
        $mixed_color_rgb = $this->mixer->mix($colors->all());
        $solution_model = $this->getClosestColor($mixed_color_rgb);
        $step->solution = $solution_model->id;
        $step->save();

        return ($step->id) ? $step->id : -1;
    }
    /**
     * Get model color closer to mixed color result
     * @param array $rgb
     * @return App\Models\Color closest color
     */
    protected function getClosestColor(array $rgb){
        list($red, $green, $blue) = $rgb;
        $closest_color = DB::table("colors")
            ->select("id", DB::raw("(ABS(red - $red) + ABS(green - $green) + ABS(blue - $blue)) as delta"))
            ->orderBy("delta")
            ->limit(1)
            ->get();
        return ($closest_color && isset($closest_color[0])) ? Color::find($closest_color[0]->id) : null;
    }
    /**
     * Returns seconds to give solution
     * @return int
     */
    public function getSeconds(): int{
        return static::SECONDS_TO_ANSWER;
    }
}