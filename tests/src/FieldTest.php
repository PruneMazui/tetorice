<?php
namespace PruneMazui\Tetrice\Tests;

use PHPUnit\Framework\TestCase;
use PruneMazui\Tetrice\GameCore\Field;
use PruneMazui\Tetrice\GameCore\Tile\TileCyan;

class FieldTest extends TestCase
{
    private function assertMap(Field $field)
    {
        $map = $field->getMap();

        for ($y = 0; $y < $field->getHeight(); $y++) {
            assertTrue(array_key_exists($y, $map));

            for ($x = 0; $x < $field->getWidth(); $x++) {
                assertTrue(array_key_exists($x, $map[$y]));
                unset($map[$y][$x]);
            }

            assertCount(0, $map[$y]);
            unset($map[$y]);
        }

        assertCount(0, $map);
    }

    public function testInitMap()
    {
        $this->assertMap(new Field());
    }

    public function testErase()
    {
        $field = new Field();

        for ($y = 0; $y < 2; $y++) {
            for ($x = 0; $x < $field->getWidth(); $x++) {
                $field->setTile($x, $y, TileCyan::getInstance());
            }
        }

        assertEquals(2, $field->erase());

        // ちゃんと消えた部分が埋められているか
        $this->assertMap($field);
    }
}
