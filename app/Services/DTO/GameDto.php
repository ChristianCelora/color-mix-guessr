<?php
namespace App\Services\DTO;

class GameDto implements IDto{
    private $game_id;

    public function __construct(int $game_id){
        $this->game_id = $game_id;
    }

    public function getId(): int{
        return $this->game_id;
    }
}


