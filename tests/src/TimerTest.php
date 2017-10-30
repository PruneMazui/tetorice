<?php
namespace PruneMazui\Tetrice\Tests;

use PHPUnit\Framework\TestCase;
use PruneMazui\Tetrice\Timer;
use PruneMazui\Tetrice\GameCore\GameOverException;

class TimerTest extends TestCase
{
    public function testTimer()
    {
        $timer = new Timer(function ($mm_sec) {
            if ($mm_sec >= 100) {
                throw new GameOverException();
            }
        });

        // 例外が飛ばないはず
        $timer->loopProcess();
        $this->addToAssertionCount(1);

        // 0.1秒後は例外が飛ぶ
        usleep(100000);
        try {
            $timer->loopProcess();
            $this->fail('Not throw GameOverException');
        } catch (GameOverException $ex) {
            $this->addToAssertionCount(1);
        }
    }
}
