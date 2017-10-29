<?php
namespace PruneMazui\Tetrice\GameCore;

use PruneMazui\Tetrice\GameCore\Tetoriminone\AbstractTetoriminone;

class Field
{
    private $width = 10;

    private $height = 20;

    /**
     * @var array [y][x]
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

    /**
     * 水平位置の中心を返す
     * @return int
     */
    public function getHorizontalCenter()
    {
        return intval($this->width / 2);
    }

    /**
     * 衝突判定
     * @param array $coordinates [[x, y], [x, y], [x, y], [x, y]]
     * @return boolean
     */
    public function isCollision($coordinates)
    {
        foreach ($coordinates as $coordinate) {
            list ($x, $y) = $coordinate;

            if ($x < 0) {
                return true;
            }

            if ($x > $this->width - 1) {
                return true;
            }

            if ($y > $this->height - 1) {
                return true;
            }

            // y座標マイナスは考慮しない
            if ($y < 0) {
                continue;
            }

            if (! is_null($this->map[$y][$x])) {
                return true;
            }
        }

        return false;
    }

    /**
     * 着地させる
     * @param AbstractTetoriminone $tetoriminone
     */
    public function land(AbstractTetoriminone $tetoriminone)
    {
        foreach ($tetoriminone->getCoordinates() as $coordinate) {
            list($x, $y) = $coordinate;
            $this->map[$y][$x] = $tetoriminone->getTile();
        }
    }

}
