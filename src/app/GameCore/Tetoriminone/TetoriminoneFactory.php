<?php
namespace PruneMazui\Tetrice\GameCore\Tetoriminone;

use PruneMazui\Tetrice\GameCore\Field;
use PruneMazui\Tetrice\Controller;

class TetoriminoneFactory
{
    private $feild;

    private $controller;

    /**
     * @param Field $feild
     * @param Controller $controller
     */
    public function __construct(Field $feild, Controller $controller)
    {
        $this->feild = $feild;
        $this->controller = $controller;
    }

    /**
     * @param int $mm_sec
     * @return \PruneMazui\Tetrice\GameCore\Tetoriminone\AbstractTetoriminone
     */
    public function create($mm_sec)
    {
        switch (mt_rand() % 7) {
            case 0:
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            default: return new OTetoriminone($this->feild, $this->controller, $mm_sec);
        }
    }
}
