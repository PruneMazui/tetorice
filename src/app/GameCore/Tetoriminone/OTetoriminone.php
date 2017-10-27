<?php
namespace PruneMazui\Tetrice\GameCore\Tetoriminone;

use PruneMazui\Tetrice\GameCore\Tile\TileYellow;

/**
 * 真四角のやつ
 */
class OTetoriminone extends AbstractTetoriminone
{
    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetrice\GameCore\Tetoriminone\AbstractTetoriminone::rotate()
     */
    protected function rotate()
    {
        // 回転しない
        return $this->coordinates;
    }

    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetrice\GameCore\Tetoriminone\AbstractTetoriminone::initCoordinates()
     */
    protected function initCoordinates($center)
    {
        return [
            [$center, 0],
            [$center + 1, 0],
            [$center, -1],
            [$center + 1, -1],
        ];
    }

    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetrice\GameCore\Tetoriminone\AbstractTetoriminone::getTile()
     */
    public function getTile()
    {
        return TileYellow::getInstance();
    }
}