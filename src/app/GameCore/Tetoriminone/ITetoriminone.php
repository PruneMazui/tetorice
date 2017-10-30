<?php
namespace PruneMazui\Tetrice\GameCore\Tetoriminone;

use PruneMazui\Tetrice\GameCore\Tile\TileCyan;
use PruneMazui\Tetrice\GameCore\Field;

/**
 * 縦長のやつ
 */
class ITetoriminone extends AbstractTetoriminone
{
    private $is_vertical = false;

    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetrice\GameCore\Tetoriminone\AbstractTetoriminone::rotate()
     */
    protected function rotate(Field $field)
    {
        $coordinates = $this->coordinates;

        list($x, $y) = $coordinates[1]; // 基準

        $next = null;

        if ($this->is_vertical) {
            // 横向き作成
            $next =  [
                [$x-1, $y],
                [$x,   $y],
                [$x+1, $y],
                [$x+2, $y],
            ];
        } else {
            // 縦向きを作成
            $next = [
                [$x, $y-1],
                [$x, $y],
                [$x, $y+1],
                [$x, $y+2],
            ];
        }

        if ($field->isCollision($next)) {
            return false;
        }

        $this->coordinates = $next;
        $this->is_vertical = ! $this->is_vertical; // 向きを反転
        return true;

    }

    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetrice\GameCore\Tetoriminone\AbstractTetoriminone::initCoordinates()
     */
    protected function getInitialCoordinates($center)
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
