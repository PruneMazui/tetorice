<?php
namespace PruneMazui\Tetris;

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
        $timer = new Timer(function ($mm_sec) use ($controller){
            $input = [];

            if ($controller->isInputLeft()) {
                $input[] = 'LEFT';
            }

            if ($controller->isInputRight()) {
                $input[] = 'RIGHT';
            }

            if ($controller->isInputDown()) {
                $input[] = 'DOWN';
            }

            if ($controller->isInputUp()) {
                $input[] = 'UP';
            }

            if ($controller->isInputRotateRight()) {
                $input[] = 'ROTATE_RIGHT';
            }

            if ($controller->isInputRotateLeft()) {
                $input[] = 'ROTATE_LEFT';
            }

            echo $mm_sec . ' : ' . implode(' ', $input) . "\n";

            $controller->clear();
        });

        // キーボード入力時エンターを回避、入力内容を出力しない
        while (true) {
            $controller->process();
            $timer->process();

            usleep(1000);
        }
    }
}
