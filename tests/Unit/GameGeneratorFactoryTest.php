<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\ColorGuessr\GameGenerator\GameGeneratorFactory;
use App\ColorGuessr\GameGenerator\{EasyGame, MediumGame, HardGame};

class GameGeneratorFactoryTest extends TestCase
{    
    public function testCreateEasyGame(): void{
        $obj = GameGeneratorFactory::create(GameGeneratorFactory::EASY_DIFFICULTY);
        $this->assertInstanceOf(EasyGame::class, $obj);
    }

    public function testCreateMediumGame(): void{
        $obj = GameGeneratorFactory::create(GameGeneratorFactory::MEDIUM_DIFFICULTY);
        $this->assertInstanceOf(MediumGame::class, $obj);
    }

    public function testCreateHardGame(): void{
        $obj = GameGeneratorFactory::create(GameGeneratorFactory::HARD_DIFFICULTY);
        $this->assertInstanceOf(HardGame::class, $obj);
    }
}
