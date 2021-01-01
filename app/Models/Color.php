<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['color_code','name','hex','red','green','blue'];
    /**
     * Returns color data without ids info
     * @return array
     */
    public function getColorAsArray(): array{
        return array(
            "color_code" => $this->color_code,
            "name" => $this->name,
            "hex" => $this->hex,
            "red" => $this->red,
            "green" => $this->green,
            "blue" => $this->blue,
        );
    }
}
