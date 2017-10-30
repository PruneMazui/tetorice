<?php
namespace PruneMazui\Tetrice\GameCore;

use PruneMazui\Tetrice\FrameProcessInterface;
use PruneMazui\Tetrice\GameCore\Tetoriminone\AbstractTetoriminone;
use PruneMazui\Tetrice\GameCore\Tetoriminone\TetoriminoneFactory;
use PruneMazui\Tetrice\Controller\Controller;
use PruneMazui\Tetrice\Config;

class GameManager implements FrameProcessInterface
{
    private static $fallLevelMap = [
        1 => 700,  // 1秒で1落ちる
        2 => 500,  // 0.8秒で1落ちる
        3 => 300,  // 0.6秒で
        4 => 100,
        5 => 50,
    ];

    private $level = 1;

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

    /**
     * @var AbstractTetoriminone
     */
    private $tetoriminone;

    /**
     * @var TetoriminoneFactory
     */
    private $factory;

    public function __construct(Controller $controller, Config $config = null)
    {
        if (!is_null($config) && array_key_exists($config->start_level, self::$fallLevelMap)) {
            $this->level = $config->start_level;
        }

        $this->controller = $controller;
        $this->field = new Field($config);
        $this->renderer = new Renderer();

        $this->factory = new TetoriminoneFactory($this->field, $controller);
    }

    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetrice\FrameProcessInterface::frameProcess()
     */
    public function frameProcess($mm_sec)
    {
        if (is_null($this->tetoriminone)) {
            $this->field->erase();
            $this->tetoriminone = $this->factory->create(self::$fallLevelMap[$this->level]);
        }

        $this->tetoriminone->frameProcess($mm_sec);

        if ($this->tetoriminone->isLand()) {
            $this->field->land($this->tetoriminone);
            $this->tetoriminone = null;
        }

        $this->renderer->render($this->field, $this->tetoriminone);
    }
}
