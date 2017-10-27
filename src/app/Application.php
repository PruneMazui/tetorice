<?php
namespace PruneMazui\Tetrice;

use PruneMazui\Tetrice\GameCore\GameManager;

class Application
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function run()
    {
        $controller = new Controller();
        $game_manager = new GameManager($controller);

        $timer = new Timer(function ($mm_sec) use ($game_manager, $controller) {
            $game_manager->frameProcess($mm_sec);

            if (_DEBUG) {
                echo "\e[0K";
                echo 'INPUT : ' . implode(', ', $controller->getInputsArray());
            }

            $controller->clear();
        });

        // キーボード入力時エンターを回避、入力内容を出力しない
        while (true) {
            $controller->loopProcess();
            $timer->loopProcess();

            usleep(1000);
        }
    }
}
