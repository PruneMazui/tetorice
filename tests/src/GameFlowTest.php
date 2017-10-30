<?php
namespace PruneMazui\Tetrice\Tests;

use PHPUnit\Framework\TestCase;
use PruneMazui\Tetrice\Application;
use PruneMazui\Tetrice\Config;

class GameFlowTest extends TestCase
{
    public function testStartToEnd()
    {
        ob_start();

        $app = new Application(new ControllerMock(), new Config([
            'is_test' => true,
            'fps' => 30,
            'start_level' => 5,
            'field_height' => 5, // 簡略化
        ]));

        $app->run();

        $content = ob_get_contents();
        ob_end_clean();

        assertContains('GAME OVER', $content);
    }
}
