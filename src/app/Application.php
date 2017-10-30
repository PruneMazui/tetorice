<?php
namespace PruneMazui\Tetrice;

use PruneMazui\Tetrice\GameCore\GameManager;
use PruneMazui\Tetrice\GameCore\GameOverException;

class Application
{
    private $config;

    private $controller;

    public function __construct(Controller $controller, $config)
    {
        $this->config = $config;
        $this->controller = $controller;
    }

    public function run()
    {
        $game_manager = new GameManager($this->controller);

        ob_start();

        $timer = new Timer(function ($mm_sec) use ($game_manager) {
            $game_manager->frameProcess($mm_sec);
            ob_flush();
        });

        try {
            while (true) {
                $controller->loopProcess();
                $timer->loopProcess();

                usleep(1000);
            }
        } catch (GameOverException $ex) {
            echo "\n!!!GAME OVER!!!\n";
        }
    }
}
