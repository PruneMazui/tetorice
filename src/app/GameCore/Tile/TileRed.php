<?php
namespace PruneMazui\Tetrice\GameCore\Tile;

class TileRed extends AbstractTile
{
    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetrice\GameCore\Tile\AbstractTile::getColorSequence()
     */
    public function getColorSequence()
    {
        return 41;
    }
}
