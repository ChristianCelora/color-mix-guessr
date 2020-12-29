<?php
namespace App\ColorGuessr\ColorMixer;

use App\Models\Color;

class RGBMixer {
    /**
     * @param array[App\Models\Color] $color model to mix
     * @return App\Models\Color model color closer to mixed colros result
     */
    public function mix(array $colors){
        // Get RGB from models
        // mix models 
        // get model color closer to mixed colros result
    }
    /**
     * RGB is addictive color model. Just do weighted mean of rgb'
     * @param array[int,int,int] array of rgb's
     * @param array[float] Optional. weights of colors. between 1 and 0
     */
    private function rgbMix(array $rgbs, $weights = array()){

    }
}