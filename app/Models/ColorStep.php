<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColorStep extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['step_id','color_id','weight'];
}
