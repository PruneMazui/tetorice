<?php
namespace PruneMazui\Tetrice\GameCore;

use PruneMazui\Tetrice\FrameProcessInterface;
use PruneMazui\Tetrice\Controller;

class GameManager implements FrameProcessInterface
{
    /**
     * @var Controller
     */
    private $controller;

    /**
     * @var Field
     */
    private $field;

    /**
     * @var Renderer
     */
    private $renderer;

    public function __construct(Controller $controller)
    {
        $this->controller = $controller;
        $this->field = new Field();
        $this->renderer = new Renderer();
    }

    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetrice\FrameProcessInterface::frameProcess()
     */
    public function frameProcess($mm_sec)
    {


        $this->renderer->render($this->field);
    }
}
