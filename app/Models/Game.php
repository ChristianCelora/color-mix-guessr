<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
    
    const EASY_DIFFICULTY = 1;

    protected $fillable = ['session_id','user_id','difficulty','current_step','ended_at'];

    public function steps(){
        return $this->hasMany(Step::class);
    }

    public function resume_step(){
        return $this->hasMany(Step::class)->where('steps.number', $this->current_step);
    }
}
