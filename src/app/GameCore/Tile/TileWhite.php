<?php
namespace PruneMazui\Tetrice\GameCore\Tile;

class TileWhite extends AbstractTile
{
    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetrice\GameCore\Tile\AbstractTile::getColorSequence()
     */
    public function getColorSequence()
    {
        return 47;
    }
}
