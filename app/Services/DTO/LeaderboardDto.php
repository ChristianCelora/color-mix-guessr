<?php
namespace App\Services\DTO;

use \Datetime;

class LeaderboardDto implements IDto{
    private $from_date;
    private $to_date;

    public function __construct(Datetime $from = null, Datetime $to = null){
        $this->from_date = $from;
        $this->to_date = $to;
    }

    public function getRanges(): array{
        return array($this->from_date, $this->to_date);
    }
}
