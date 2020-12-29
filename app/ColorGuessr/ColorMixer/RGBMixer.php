<?php
namespace App\ColorGuessr\ColorMixer;

class RGBMixer {
    /**
     * @param array[App\Models\Color] $color model to mix
     * @param array[float] Optional. weights of colors. between 1 and 0
     * @return array[int] RGB array
     */
    public function mix(array $colors, $weights = array()): array{
        // Get RGB from models
        $rgbs = array();
        foreach($colors as $c){
            $rgbs[] = array($c->red, $c->blue, $c->green);
        }
        // mix models 
        if(empty($weights)){
            $weights = array_fill(0, count($rgbs), (float)1/count($rgbs));
        }
        return $this->rgbMix($rgbs, $weights);
    }
    /**
     * RGB is addictive color model. Just do weighted mean of rgb'
     * @param array[int,int,int] array of rgb's
     * @param array[float] Weights of colors. between 1 and 0
     * @return array rgb result color
     */
    private function rgbMix(array $rgbs, array $weights): array{
        list($r,$g,$b) = array(array(),array(),array());
        
        for($i = 0; $i < count($rgbs); $i++){
            $r[] = $rgbs[$i][0] * $weights[$i];
            $g[] = $rgbs[$i][1] * $weights[$i];
            $b[] = $rgbs[$i][2] * $weights[$i];
        }
        $res = array(
            intval(array_sum($r)),
            intval(array_sum($g)),
            intval(array_sum($b))
        );

        return $res;
    }
}