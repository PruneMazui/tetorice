<?php
namespace PruneMazui\Tetrice\GameCore\Tetoriminone;

use PruneMazui\Tetrice\GameCore\Field;
use PruneMazui\Tetrice\Controller\Controller;

class TetoriminoneFactory
{
    private $field;

    private $controller;

    /**
     * @param Field $field
     * @param Controller $controller
     */
    public function __construct(Field $field, Controller $controller)
    {
        $this->field = $field;
        $this->controller = $controller;
    }

    /**
     * @param int $fall_speed
     * @return \PruneMazui\Tetrice\GameCore\Tetoriminone\AbstractTetoriminone
     */
    public function create($fall_speed)
    {
        $field = $this->field;
        $controller = $this->controller;

        switch (mt_rand() % 7) {
            case 0: return new ITetoriminone($field, $controller, $fall_speed);
            case 1: return new OTetoriminone($field, $controller, $fall_speed);
            case 2: return new STetoriminone($field, $controller, $fall_speed);
            case 3: return new ZTetoriminone($field, $controller, $fall_speed);
            case 4: return new JTetoriminone($field, $controller, $fall_speed);
            case 5: return new LTetoriminone($field, $controller, $fall_speed);
            default: return new TTetoriminone($field, $controller, $fall_speed);
        }
    }
}
