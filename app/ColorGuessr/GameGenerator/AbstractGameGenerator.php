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

    public function createGame(string $session_id, int $user_id = -1 ){
        $game = new Game();
        $game->session_id = $session_id;
        if($user_id > 0){
            $game->user_id = $user_id;
        }
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
        $weights = $this->calculateWeights(static::N_COLORS_INPUT, static::MIN_WEIGHT, static::MAX_WEIGHT);
        $i = 0;
        foreach($colors as $color){
            $color_step = new ColorStep();
            $color_step->color_id = $color->id;
            $color_step->step_id = $step->id;
            $color_step->weight = $weights[$i];
            $color_step->save();
            unset($color_step);
            $i++;
        }
        // Calculate & update solution
        $mixed_color_rgb = $this->mixer->mix($colors->all(), $weights);
        $solution_model = $this->getClosestColor($mixed_color_rgb);
        $step->solution = $solution_model->id;
        $step->save();

        return ($step->id) ? $step->id : -1;
    }
    /**
     * @param int $n_weights
     * @param float $min          Optional. Minuim weight. Range [0,1]
     * @param float $max          Optional. Maxium weight. Range [0,1]
     * @return array Array of weights. each weight int [0,1]
     */
    protected function calculateWeights(int $n_weights, $min = 0, $max = 0): array{
        $weights = array();
        $left = 1;
        for($i = 0; $i < $n_weights-1; $i++){
            $w = round(rand($min*100, $max*100) / 100,2);
            $left -= $w;
            $weights[] = $w;
            unset($w);
        }
        $weights[] = $left; // Last weight has remaining
        return $weights;
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