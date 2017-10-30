<?php
namespace PruneMazui\Tetrice\GameCore\Tetoriminone;

use PruneMazui\Tetrice\GameCore\Field;
use PruneMazui\Tetrice\Controller\AbstractController;

class TetoriminoneFactory
{
    private $field;

    private $controller;

    /**
     * @param Field $field
     * @param AbstractController $controller
     */
    public function __construct(Field $field, AbstractController $controller)
    {
        $this->field = $field;
        $this->controller = $controller;
    }

    /**
     * @param int $mm_sec
     * @return \PruneMazui\Tetrice\GameCore\Tetoriminone\AbstractTetoriminone
     */
    public function create($mm_sec)
    {
        $field = $this->field;
        $controller = $this->controller;

        switch (mt_rand() % 7) {
            case 0: return new ITetoriminone($field, $controller, $mm_sec);
            case 1: return new OTetoriminone($field, $controller, $mm_sec);
            case 2: return new STetoriminone($field, $controller, $mm_sec);
            case 3: return new ZTetoriminone($field, $controller, $mm_sec);
            case 4: return new JTetoriminone($field, $controller, $mm_sec);
            case 5: return new LTetoriminone($field, $controller, $mm_sec);
            default: return new TTetoriminone($field, $controller, $mm_sec);
        }
    }
}
