<?php
namespace PruneMazui\Tetrice;

use PruneMazui\Tetrice\GameCore\GameManager;
use PruneMazui\Tetrice\GameCore\GameOverException;

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

        $timer = new Timer(function ($mm_sec) use ($game_manager) {
            $game_manager->frameProcess($mm_sec);
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
