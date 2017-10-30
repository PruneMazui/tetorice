<?php
namespace PruneMazui\Tetrice;

/**
 * @property bool $is_test
 * @property int $fps
 * @property int $start_level
 * @property int $field_width
 * @property int $field_height
 */
class Config
{
    public function __construct(array $config = [])
    {
        $this->setConfig($config);
    }

    public function setConfig(array $config = [])
    {
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
    }

    private $is_test = false;

    private $fps = 30;

    private $start_level = 1;

    private $field_width = 10;

    private $field_height = 20;

    public function __get($name)
    {
        return isset($this->$name) ? $this->$name : null;
    }
}
