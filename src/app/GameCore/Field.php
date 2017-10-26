<?php
namespace PruneMazui\Tetrice\GameCore;

class Field
{
    private $width = 10;

    private $height = 20;

    /**
     * @var array [w_px][h_px]
     */
    private $map = [];

    public function __construct()
    {
        // マップを初期化
        for ($i = 0; $i < $this->width; $i++) {
            $this->map[$i] = [];

            for ($j = 0; $i < $this->height; $j++) {
                $this->map[$i][$j] = null;
            }
        }
    }

}
