<?php
namespace PruneMazui\Tetrice\GameCore\Tetoriminone;

use PruneMazui\Tetrice\GameCore\Tile\TileCyan;

/**
 * 縦長のやつ
 */
class ITetoriminone extends AbstractTetoriminone
{
    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetrice\GameCore\Tetoriminone\AbstractTetoriminone::rotate()
     */
    protected function rotate()
    {
        $coordinates = $this->coordinates;

        // 現在の向きを取得
        $is_vertical = $coordinates[0][0] == $coordinates[1][0];

        list($x, $y) = $coordinates[1]; // 基準

        if ($is_vertical) {
            // 横向きを返す
            return [
                [$x-1, $y],
                [$x,   $y],
                [$x+1, $y],
                [$x+2, $y],
            ];
        }

        // 縦向きを返す
        return [
            [$x, $y-1],
            [$x, $y],
            [$x, $y+1],
            [$x, $y+2],
        ];

    }

    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetrice\GameCore\Tetoriminone\AbstractTetoriminone::initCoordinates()
     */
    protected function initCoordinates($center)
    {
        return [
            [$center-1, 0],
            [$center,   0],
            [$center+1, 0],
            [$center+2, 0],
        ];
    }

    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetrice\GameCore\Tetoriminone\AbstractTetoriminone::getTile()
     */
    public function getTile()
    {
        return TileCyan::getInstance();
    }
}
