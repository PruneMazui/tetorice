<?php
namespace PruneMazui\Tetrice\GameCore\Tile;

abstract class AbstractTile
{
    /**
     * 背景色のANSIカラーシーケンス番号を返す
     *
     * @return int
     */
    abstract public function getColorSequence();

    public function __toString()
    {
        return "\e[" . $this->getColorSequence() . "m　\e[m";
    }
}
