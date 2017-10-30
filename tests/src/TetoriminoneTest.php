<?php
namespace PruneMazui\Tetrice\Tests;

use PHPUnit\Framework\TestCase;
use PruneMazui\Tetrice\GameCore\Field;
use PruneMazui\Tetrice\GameCore\Tile\TileCyan;
use PruneMazui\Tetrice\Tests\ControllerMock;
use PruneMazui\Tetrice\GameCore\GameOverException;
use PruneMazui\Tetrice\Controller\Controller;
use PruneMazui\Tetrice\GameCore\Tetoriminone\AbstractTetoriminone;
use PruneMazui\Tetrice\GameCore\Tetoriminone\ITetoriminone;
use PruneMazui\Tetrice\GameCore\Tetoriminone\JTetoriminone;
use PruneMazui\Tetrice\GameCore\Tetoriminone\LTetoriminone;
use PruneMazui\Tetrice\GameCore\Tetoriminone\OTetoriminone;
use PruneMazui\Tetrice\GameCore\Tetoriminone\ZTetoriminone;
use PruneMazui\Tetrice\GameCore\Tetoriminone\STetoriminone;
use PruneMazui\Tetrice\GameCore\Tetoriminone\TTetoriminone;
use PruneMazui\Tetrice\GameCore\Tile\AbstractTile;

class TetoriminoneTest extends TestCase
{
    const FALL_SPEED_FOR_TEST = 1000;

    private $controller;

    protected function setUp()
    {
        $this->controller = new ControllerMock();
    }

    /**
     * @return [string, int][] [クラス名, 回転種別数][]
     */
    public function tetoriminoneProvider()
    {
        return [
            [ITetoriminone::class, 2],
            [JTetoriminone::class, 4],
            [LTetoriminone::class, 4],
            [OTetoriminone::class, 1],
            [STetoriminone::class, 2],
            [TTetoriminone::class, 4],
            [ZTetoriminone::class, 2],
        ];
    }

    /**
     * @dataProvider tetoriminoneProvider
     */
    public function testGameOver($class, $dummy)
    {
        $field = new Field();

        // 一番上を全部埋める
        for ($x = 0; $x < $field->getWidth(); $x++) {
            $field->setTile($x, 0, TileCyan::getInstance());
        }

        $tetoriminone = new $class($field, $this->controller, self::FALL_SPEED_FOR_TEST);
        assertTrue($tetoriminone instanceof AbstractTetoriminone);

        try {
            $tetoriminone->frameProcess(0);
            $this->fail('Fail to throw GameOverException');
        } catch (GameOverException $ex) {
            $this->addToAssertionCount(1);
        }
    }

    /**
     * @dataProvider tetoriminoneProvider
     */
    public function testFall($class, $dummy)
    {
        $field = new Field();

        $speed = self::FALL_SPEED_FOR_TEST;

        // =====================================
        // 自然落下テスト
        $tetoriminone = new $class($field, $this->controller, $speed);
        assertTrue($tetoriminone instanceof AbstractTetoriminone);

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
        assertFalse($tetoriminone->isLand());

        // =====================================
        // 下キーによるテスト
        $this->controller->clear();
        $tetoriminone = new $class($field, $this->controller, $speed);
        assertTrue($tetoriminone instanceof AbstractTetoriminone);
        $tetoriminone->frameProcess(0);

        $first = $tetoriminone->getCoordinates();
        $this->controller->setInputs(Controller::DOWN);
        assertTrue($this->controller->isInputDown());

        // 落下してない
        $tetoriminone->frameProcess(AbstractTetoriminone::INPUT_MINIMAL_INTERVAL_MM_SEC - 1);
        assertEquals($first, $tetoriminone->getCoordinates());
        assertTrue($this->controller->isInputDown());

        // 落下してる
        $tetoriminone->frameProcess(AbstractTetoriminone::INPUT_MINIMAL_INTERVAL_MM_SEC);
        $next = $tetoriminone->getCoordinates();
        assertNotEquals($first, $next);
        assertEquals($first[0][1] + 1, $next[0][1]); // y座標が+1
        assertEquals($first[1][1] + 1, $next[1][1]); // y座標が+1
        assertEquals($first[2][1] + 1, $next[2][1]); // y座標が+1
        assertEquals($first[3][1] + 1, $next[3][1]); // y座標が+1
        assertFalse($this->controller->isInputDown());
        assertFalse($tetoriminone->isLand());

        // 高さ分時間経過
        for ($i = 0; $i <= $field->getHeight(); $i++) {
            $this->controller->setInputs(Controller::DOWN);
            $tetoriminone->frameProcess(AbstractTetoriminone::INPUT_MINIMAL_INTERVAL_MM_SEC * ($i + 1));
        }
        assertTrue($tetoriminone->isLand());

        // =====================================
        // 上キーによるテスト
        $this->controller->clear();
        $tetoriminone = new $class($field, $this->controller, $speed);
        assertTrue($tetoriminone instanceof AbstractTetoriminone);
        $tetoriminone->frameProcess(0);

        $first = $tetoriminone->getCoordinates();
        $this->controller->setInputs(Controller::UP);
        assertTrue($this->controller->isInputUp());

        // 落下してない
        $tetoriminone->frameProcess(AbstractTetoriminone::INPUT_MINIMAL_INTERVAL_MM_SEC - 1);
        assertEquals($first, $tetoriminone->getCoordinates());
        assertTrue($this->controller->isInputUp());

        // 落下してる
        $tetoriminone->frameProcess(AbstractTetoriminone::INPUT_MINIMAL_INTERVAL_MM_SEC);
        $next = $tetoriminone->getCoordinates();
        assertNotEquals($first, $next);

        $next_y = [];
        foreach ($next as $cordinate) {
            list($x, $y) = $cordinate;
            $next_y[] = $y;
        }
        assertContains($field->getHeight()-1, $next_y); // y座標が一番のタイルが存在

        assertFalse($this->controller->isInputUp());
        assertTrue($tetoriminone->isLand());
    }

    /**
     * @dataProvider tetoriminoneProvider
     */
    public function testMoveRight($class, $dummy)
    {
        $field = new Field();

        $speed = self::FALL_SPEED_FOR_TEST;

        $this->controller->clear();
        $tetoriminone = new $class($field, $this->controller, $speed);
        assertTrue($tetoriminone instanceof AbstractTetoriminone);
        $tetoriminone->frameProcess(0);

        $first = $tetoriminone->getCoordinates();
        $this->controller->setInputs(Controller::RIGHT);
        assertTrue($this->controller->isInputRight());

        // 動いてない
        $tetoriminone->frameProcess(AbstractTetoriminone::INPUT_MINIMAL_INTERVAL_MM_SEC - 1);
        assertEquals($first, $tetoriminone->getCoordinates());
        assertTrue($this->controller->isInputRight());

        // 動いてる
        $tetoriminone->frameProcess(AbstractTetoriminone::INPUT_MINIMAL_INTERVAL_MM_SEC);
        $next = $tetoriminone->getCoordinates();
        assertNotEquals($first, $next);
        assertEquals($first[0][0] + 1, $next[0][0]); // x座標が+1
        assertEquals($first[1][0] + 1, $next[1][0]); // x座標が+1
        assertEquals($first[2][0] + 1, $next[2][0]); // x座標が+1
        assertEquals($first[3][0] + 1, $next[3][0]); // x座標が+1
        assertFalse($this->controller->isInputRight());

        // 動くの失敗ケース
        $tetoriminone = new $class($field, $this->controller, $speed);
        assertTrue($tetoriminone instanceof AbstractTetoriminone);
        $tetoriminone->frameProcess(0);
        $first = $tetoriminone->getCoordinates();

        for ($x = 0; $x < $field->getWidth(); $x++) { // 全部埋める
            for ($y = 0; $y < $field->getHeight(); $y++) {
                $field->setTile($x, $y, TileCyan::getInstance());
            }
        }

        $this->controller->setInputs(Controller::RIGHT);
        assertTrue($this->controller->isInputRight());
        $tetoriminone->frameProcess(AbstractTetoriminone::INPUT_MINIMAL_INTERVAL_MM_SEC);

        assertEquals($first, $tetoriminone->getCoordinates());
        assertFalse($this->controller->isInputRight());
    }

    /**
     * @dataProvider tetoriminoneProvider
     */
    public function testMoveLeft($class, $dummy)
    {
        $field = new Field();

        $speed = self::FALL_SPEED_FOR_TEST;

        $this->controller->clear();
        $tetoriminone = new $class($field, $this->controller, $speed);
        assertTrue($tetoriminone instanceof AbstractTetoriminone);
        $tetoriminone->frameProcess(0);

        $first = $tetoriminone->getCoordinates();
        $this->controller->setInputs(Controller::LEFT);
        assertTrue($this->controller->isInputLeft());

        // 動いてない
        $tetoriminone->frameProcess(AbstractTetoriminone::INPUT_MINIMAL_INTERVAL_MM_SEC - 1);
        assertEquals($first, $tetoriminone->getCoordinates());
        assertTrue($this->controller->isInputLeft());

        // 動いてる
        $tetoriminone->frameProcess(AbstractTetoriminone::INPUT_MINIMAL_INTERVAL_MM_SEC);
        $next = $tetoriminone->getCoordinates();
        assertNotEquals($first, $next);
        assertEquals($first[0][0] - 1, $next[0][0]); // x座標が-1
        assertEquals($first[1][0] - 1, $next[1][0]); // x座標が-1
        assertEquals($first[2][0] - 1, $next[2][0]); // x座標が-1
        assertEquals($first[3][0] - 1, $next[3][0]); // x座標が-1
        assertFalse($this->controller->isInputLeft());

        // 動くの失敗ケース
        $tetoriminone = new $class($field, $this->controller, $speed);
        assertTrue($tetoriminone instanceof AbstractTetoriminone);
        $tetoriminone->frameProcess(0);
        $first = $tetoriminone->getCoordinates();

        for ($x = 0; $x < $field->getWidth(); $x++) { // 全部埋める
            for ($y = 0; $y < $field->getHeight(); $y++) {
                $field->setTile($x, $y, TileCyan::getInstance());
            }
        }

        $this->controller->setInputs(Controller::LEFT);
        assertTrue($this->controller->isInputLeft());
        $tetoriminone->frameProcess(AbstractTetoriminone::INPUT_MINIMAL_INTERVAL_MM_SEC);

        assertEquals($first, $tetoriminone->getCoordinates());
        assertFalse($this->controller->isInputLeft());
    }

    /**
     * @dataProvider tetoriminoneProvider
     */
    public function testRotate($class, $rotation_kind)
    {
        $field = new Field();

        $speed = self::FALL_SPEED_FOR_TEST;

        $this->controller->clear();
        $tetoriminone = new $class($field, $this->controller, $speed);
        assertTrue($tetoriminone instanceof AbstractTetoriminone);
        $tetoriminone->frameProcess(0);

        $first = $tetoriminone->getCoordinates();
        $this->controller->setInputs(Controller::ROTATE_RIGHT);
        assertTrue($this->controller->isInputRotateRight());

        // 動いてない
        $tetoriminone->frameProcess(AbstractTetoriminone::INPUT_MINIMAL_INTERVAL_MM_SEC - 1);
        assertEquals($first, $tetoriminone->getCoordinates());
        assertTrue($this->controller->isInputRotateRight());

        $next_process = AbstractTetoriminone::INPUT_MINIMAL_INTERVAL_MM_SEC;

        // ローテーションに達するまで違う形になる
        for ($i = 1; $i < $rotation_kind; $i++) {
            $tetoriminone->frameProcess($next_process);
            assertNotEquals($first, $tetoriminone->getCoordinates());
            assertFalse($this->controller->isInputRotateRight());

            $this->controller->setInputs(Controller::ROTATE_RIGHT);
            $next_process += AbstractTetoriminone::INPUT_MINIMAL_INTERVAL_MM_SEC;
        }

        // 元の形に戻っている
        $tetoriminone->frameProcess($next_process);
        assertEquals($first, $tetoriminone->getCoordinates());
        assertFalse($this->controller->isInputRotateRight());

        // 回転失敗ケース
        $tetoriminone = new $class($field, $this->controller, $speed);
        assertTrue($tetoriminone instanceof AbstractTetoriminone);
        $tetoriminone->frameProcess(0);
        $first = $tetoriminone->getCoordinates();

        for ($x = 0; $x < $field->getWidth(); $x++) { // 全部埋める
            for ($y = 0; $y < $field->getHeight(); $y++) {
                $field->setTile($x, $y, TileCyan::getInstance());
            }
        }

        $this->controller->setInputs(Controller::ROTATE_RIGHT);
        assertTrue($this->controller->isInputRotateRight());
        $tetoriminone->frameProcess(AbstractTetoriminone::INPUT_MINIMAL_INTERVAL_MM_SEC);

        assertEquals($first, $tetoriminone->getCoordinates());
        assertFalse($this->controller->isInputRotateRight());
    }

    /**
     * @dataProvider tetoriminoneProvider
     */
    public function testTile($class, $dummy)
    {
        $field = new Field();

        $speed = self::FALL_SPEED_FOR_TEST;

        $this->controller->clear();
        $tetoriminone = new $class($field, $this->controller, $speed);
        assertTrue($tetoriminone instanceof AbstractTetoriminone);
        assertInstanceOf(AbstractTile::class, $tetoriminone->getTile());
    }
}
