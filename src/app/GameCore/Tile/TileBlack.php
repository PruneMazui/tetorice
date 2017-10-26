<?php
namespace PruneMazui\Tetrice\GameCore\Tile;

class TileBlack extends AbstractTile
{
    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetrice\GameCore\Tile\AbstractTile::getColorSequence()
     */
    public function getColorSequence()
    {
        return 40;
    }
}
