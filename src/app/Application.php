<?php
namespace PruneMazui\Tetrice;

use PruneMazui\Tetrice\GameCore\GameManager;
use PruneMazui\Tetrice\GameCore\GameOverException;
use PruneMazui\Tetrice\Controller\ControllerKeyboard;

class Application
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function run()
    {
        $controller = new ControllerKeyboard();
        $game_manager = new GameManager($controller);

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
