<?php
namespace App\ColorGuessr\GameGenerator;

class GameGeneratorFactory {
    const EASY_DIFFICULTY = 1;
    const MEDIUM_DIFFICULTY = 2;
    const HARD_DIFFICULTY = 3;
    /**
     * Factory pattern
     * @param int $difficulty Game difficulty
     * @return AbstractGameGenerator|null return instance. Null on error
     */
    public static function create(int $difficulty): AbstractGameGenerator {
        $obj = null;
        
        switch($difficulty){
            case self::EASY_DIFFICULTY:
                $obj = new EasyGame();
                break;
            case self::MEDIUM_DIFFICULTY:
                $obj = new MediumGame();
                break;
            case self::HARD_DIFFICULTY:
                $obj = new HardGame();
                break;
        }

        return $obj;
    }
}