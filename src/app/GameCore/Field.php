<?php
namespace PruneMazui\Tetrice\GameCore;

class Field
{
    private $width = 10;

    private $height = 20;

    /**
     * @var array [h_px][w_px]
     */
    private $map = [];

    public function __construct()
    {
        // マップを初期化
        for ($i = 0; $i < $this->height; $i++) {
            $this->map[$i] = [];

            for ($j = 0; $j < $this->width; $j++) {
                $this->map[$i][$j] = null;
            }
        }
    }

    /**
     * @return array [h_px][w_px]
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

}
