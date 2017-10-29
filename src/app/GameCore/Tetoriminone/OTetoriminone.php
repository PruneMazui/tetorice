<?php
namespace PruneMazui\Tetrice\GameCore\Tetoriminone;

use PruneMazui\Tetrice\GameCore\Tile\TileYellow;
use PruneMazui\Tetrice\GameCore\Field;

/**
 * 真四角のやつ
 */
class OTetoriminone extends AbstractTetoriminone
{
    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetrice\GameCore\Tetoriminone\AbstractTetoriminone::rotate()
     */
    protected function rotate(Field $field)
    {
        // noop
        return true;
    }

    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetrice\GameCore\Tetoriminone\AbstractTetoriminone::initCoordinates()
     */
    protected function getInitialCoordinates($center)
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
