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

    protected function __construct()
    {}

    protected static $instance = [];

    /**
     * @return static
     */
    public static function getInstance()
    {
        $class = get_called_class();

        if (! isset(self::$instance[$class])) {
            self::$instance[$class] = new static();
        }

        return self::$instance[$class];
    }

    /**
     *
     * @param string $str
     * @param array $other_sequence
     * @return string
     */
    public function make($str, array $other_sequence = [])
    {
        $pre_sequence = "";
        if (count($other_sequence)) {
            $pre_sequence = implode(';', $other_sequence) . ';';
        }

        return "\e[{$pre_sequence}" . $this->getColorSequence() . "m{$str}\e[m";
    }

    public function __toString()
    {
        return $this->make('  ');
    }
}
