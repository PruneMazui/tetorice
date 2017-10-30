<?php
namespace PruneMazui\Tetrice\GameCore\Tetoriminone;

use PruneMazui\Tetrice\GameCore\Field;
use PruneMazui\Tetrice\GameCore\Tile\TileGreen;

/**
 *   ■■
 * ■■   こんなやつ
 */
class STetoriminone extends AbstractTetoriminone
{
    private $is_vertical = false;

    private function makeHorizontal($x, $y)
    {
        return [
            [$x-1,  $y],
            [$x,    $y],
            [$x,    $y-1],
            [$x+1,  $y-1]
        ];
    }

    private function makeVertical($x, $y)
    {
        return [
            [$x,    $y+1],
            [$x,    $y],
            [$x-1,    $y],
            [$x-1,  $y-1]
        ];
    }

    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetrice\GameCore\Tetoriminone\AbstractTetoriminone::rotate()
     */
    protected function rotate(Field $field)
    {
        $coordinates = $this->coordinates;

        list($x, $y) = $coordinates[1]; // 基準

        $next = $this->is_vertical ? $this->makeHorizontal($x, $y) : $this->makeVertical($x, $y);

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
        return $this->makeHorizontal($center, 0);
    }

    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetrice\GameCore\Tetoriminone\AbstractTetoriminone::getTile()
     */
    public function getTile()
    {
        return TileGreen::getInstance();
    }
}
