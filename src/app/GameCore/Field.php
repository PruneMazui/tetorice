<?php
namespace PruneMazui\Tetrice\GameCore;

use PruneMazui\Tetrice\GameCore\Tetoriminone\AbstractTetoriminone;
use PruneMazui\Tetrice\GameCore\Tile\AbstractTile;

class Field
{
    private $width = 10;

    private $height = 20;

    /**
     * @var array (AbstractTile|null)[y][x]
     */
    private $map = [];

    public function __construct()
    {
        // マップを初期化
        for ($i = 0; $i < $this->height; $i++) {
            $this->map[$i] = array_fill(0, $this->width, null);
        }
    }

    /**
     * @param int $x
     * @param int $y
     * @param AbstractTile $tile
     */
    public function setTile($x, $y, AbstractTile $tile)
    {
        assert('array_key_exists($y, $this->map)');
        assert('array_key_exists($x, $this->map[$y])');

        $this->map[$y][$x] = $tile;
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

            if ($x < 0 || $x >= $this->width) {
                continue;
            }

            if ($y < 0 || $y >= $this->height) {
                continue;
            }

            $this->map[$y][$x] = $tetoriminone->getTile();
        }
    }

    /**
     * 揃ってるラインを消す
     * @return int 消したライン数
     */
    public function erase()
    {
        $erase_count = 0;
        $new = [];

        foreach ($this->map as $y => $line) {
            $is_erasable = true;

            foreach ($line as $y => $value) {
                if (is_null($value)) {
                    $is_erasable = false;
                    break;
                }
            }

            if (! $is_erasable) {
                $new[] = $line;
                continue;
            }

            $erase_count++;
        }

        for ($i = 0; $i < $erase_count; $i++) {
            array_unshift($new, array_fill(0, $this->width, null));
        }

        $this->map = $new;
        return $erase_count;
    }

}
