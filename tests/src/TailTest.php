<?php
namespace PruneMazui\Tetrice\Tests;

use PHPUnit\Framework\TestCase;
use PruneMazui\Tetrice\GameCore\Tile\TileBlack;
use PruneMazui\Tetrice\GameCore\Tile\TileBlue;
use PruneMazui\Tetrice\GameCore\Tile\TileCyan;
use PruneMazui\Tetrice\GameCore\Tile\TileGreen;
use PruneMazui\Tetrice\GameCore\Tile\TileMagenta;
use PruneMazui\Tetrice\GameCore\Tile\TileRed;
use PruneMazui\Tetrice\GameCore\Tile\TileWhite;
use PruneMazui\Tetrice\GameCore\Tile\TileYellow;
use PruneMazui\Tetrice\GameCore\Tile\AbstractTile;

class TailTest extends TestCase
{
    public function tileProvider()
    {
        return [
            [TileBlack::getInstance()],
            [TileBlue::getInstance()],
            [TileCyan::getInstance()],
            [TileGreen::getInstance()],
            [TileMagenta::getInstance()],
            [TileRed::getInstance()],
            [TileWhite::getInstance()],
            [TileYellow::getInstance()],
        ];
    }

    /**
     * @dataProvider tileProvider
     */
    public function testTile(AbstractTile $tile)
    {
        $color_seq = $tile->getColorSequence();

        $make = $tile->make('hoge');
        assertContains((string)$color_seq, $make);
        assertContains('hoge', $make);

        $str = (string)$tile;
        assertContains((string)$color_seq, $str);
        assertContains('  ', $str);
    }
}
