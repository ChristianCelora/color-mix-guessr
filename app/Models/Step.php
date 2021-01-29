<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\ColorGuessr\ColorConverter\ColorConverter;

class Step extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['game_id','number','solution','user_guess','score'];

    public function game(){
        return $this->belongsTo(Game::class);
    }

    public function colors(){
        return $this->belongsToMany(
            Color::class, 
            ColorStep::class, 
            'step_id', 
            'id'
        )->withPivot("weight");
    }

    public function getStepResultData(): array{
        return array(
            "solution" => Color::find($this->solution)->getColorAsArray(),
            "number" => $this->number,
            "user_guess" => array("hex" => $this->user_guess, "rgb" => ColorConverter::hexToRgb($this->user_guess)),
            "score" => $this->score,
        );
    }
}
