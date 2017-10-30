<?php
namespace PruneMazui\Tetrice\Tests\GameCore\Tetoriminone;

use PHPUnit\Framework\TestCase;
use PruneMazui\Tetrice\GameCore\Field;
use PruneMazui\Tetrice\GameCore\Tile\TileCyan;
use PruneMazui\Tetrice\Tests\ControllerMock;
use PruneMazui\Tetrice\GameCore\GameOverException;
use PruneMazui\Tetrice\GameCore\Tetoriminone\ITetoriminone;
use PruneMazui\Tetrice\Controller\Controller;

class TetoriminoneTest extends TestCase
{
    private $controller;

    protected function setUp()
    {
        $this->controller = new ControllerMock();
    }

    public function testGameOver()
    {
        $field = new Field();

        // 一番上を全部埋める
        for ($x = 0; $x < $field->getWidth(); $x++) {
            $field->setTile($x, 0, TileCyan::getInstance());
        }

        $tetoriminone = new ITetoriminone($field, $this->controller, 100);
        try {
            $tetoriminone->frameProcess(0);
            $this->fail('Fail to throw GameOverException');
        } catch (GameOverException $ex) {
            $this->addToAssertionCount(1);
        }
    }

    public function testFall()
    {
        $field = new Field();

        $speed = 1000;

        // =====================================
        // 自然落下テスト
        $tetoriminone = new ITetoriminone($field, $this->controller, $speed);
        $tetoriminone->frameProcess(0);
        $first = $tetoriminone->getCoordinates();

        // 落下してない
        $tetoriminone->frameProcess($speed - 1);
        assertEquals($first, $tetoriminone->getCoordinates());

        // 落下してる
        $tetoriminone->frameProcess($speed);
        $next = $tetoriminone->getCoordinates();
        assertNotEquals($first, $next);
        assertEquals(1, $next[1][1]); // y座標が+1

        // =====================================
        // 下キーによるテスト
        $this->controller->clear();
        $tetoriminone = new ITetoriminone($field, $this->controller, $speed);
        $tetoriminone->frameProcess(0);

        $first = $tetoriminone->getCoordinates();

        // 落下してない
        $tetoriminone->frameProcess(100);
        assertEquals($first, $tetoriminone->getCoordinates());

        // 落下してる
        $this->controller->setInputs(Controller::DOWN);
        $tetoriminone->frameProcess(100);
        $next = $tetoriminone->getCoordinates();
        assertNotEquals($first, $next);
        assertEquals(1, $next[1][1]); // y座標が+1

        // =====================================
        // 上キーによるテスト
        $this->controller->clear();
        $tetoriminone = new ITetoriminone($field, $this->controller, $speed);
        $tetoriminone->frameProcess(0);

        $first = $tetoriminone->getCoordinates();

        // 落下してない
        $tetoriminone->frameProcess(100);
        assertEquals($first, $tetoriminone->getCoordinates());

        // 落下してる
        $this->controller->setInputs(Controller::UP);
        $tetoriminone->frameProcess(100);
        $next = $tetoriminone->getCoordinates();
        assertNotEquals($first, $next);
        assertEquals($field->getHeight()-1, $next[1][1]); // y座標が一番下
    }
}