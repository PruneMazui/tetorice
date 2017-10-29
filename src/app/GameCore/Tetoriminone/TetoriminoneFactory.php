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
        $field = $this->feild;
        $controller = $this->controller;

        switch (mt_rand() % 7) {
            case 0:
            case 1: return new STetoriminone($field, $controller, $mm_sec);
            case 2: return new ITetoriminone($field, $controller, $mm_sec);
            case 3:
            case 4: return new ZTetoriminone($field, $controller, $mm_sec);
            case 5:
            default: return new OTetoriminone($field, $controller, $mm_sec);
        }
    }
}
