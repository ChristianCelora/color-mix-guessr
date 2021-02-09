<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use \ReflectionClass;
use \DateTime;

use App\Services\LeaderboardService;
use App\Services\DTO\LeaderboardDto;

class LeaderboardServiceTest extends TestCase {
    /**
     * Test private / protected methods
     * @param string $name method name
     */
    protected static function getMethod($name){
        $class = new ReflectionClass("App\Services\LeaderboardService");
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    public function testMakeMethod(): LeaderboardService{
        $from = new DateTime();
        $to = new DateTime();
        $leaderboard_service = LeaderboardService::make(new LeaderboardDto($from, $to));
        $this->assertInstanceOf(LeaderboardService::class, $leaderboard_service);
        return $leaderboard_service;
    }
    /**
     * @depends testMakeMethod
     */
    public function testMakeLeaderboard(LeaderboardService $service){
        $test_method = self::getMethod("makeLeaderboard");
        $leaderboard = $test_method->invokeArgs($service, array());
        $this->assertIsArray($leaderboard);
    }
}
