<?php
namespace PruneMazui\Tetrice;

use PruneMazui\Tetrice\GameCore\GameManager;
use PruneMazui\Tetrice\GameCore\GameOverException;
use PruneMazui\Tetrice\Controller\Controller;

class Application
{
    private $config;

    private $controller;

    public function __construct(Controller $controller, Config $config)
    {
        $this->config = $config;
        $this->controller = $controller;
    }

    public function run()
    {
        $game_manager = new GameManager($this->controller, $this->config);

        ob_start();

        $timer = new Timer(function ($mm_sec) use ($game_manager) {
            $game_manager->frameProcess($mm_sec);
            ob_flush();
        }, $this->config->fps);

        try {
            while (true) {
                $this->controller->loopProcess();
                $timer->loopProcess();

                usleep(1000);
            }
        } catch (GameOverException $ex) {
            echo "\n!!!GAME OVER!!!\n";
        }
        ob_end_flush();
    }
}
