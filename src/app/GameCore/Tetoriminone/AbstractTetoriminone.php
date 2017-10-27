<?php
namespace PruneMazui\Tetrice\GameCore\Tetoriminone;

use PruneMazui\Tetrice\GameCore\Field;
use PruneMazui\Tetrice\Controller;
use PruneMazui\Tetrice\FrameProcessInterface;
use PruneMazui\Tetrice\GameCore\GameOverException;
use PruneMazui\Tetrice\GameCore\Tile\AbstractTile;

abstract class AbstractTetoriminone implements FrameProcessInterface
{
    private static $fallLevelMap = [
        1 => 1000, // 1秒で1落ちる
        2 => 600,  // 0.8秒で1落ちる
        3 => 300,  // 0.6秒で
        4 => 100,
        5 => 50,
    ];

    private static $level = 1;

    protected $feild;

    protected $controller;

    private $create_mm_sec;

    private $pre_fall_mm_sec;
    private $pre_move_mm_sec;
    private $pre_rotate_mm_sec;

    /**
     * @var array [[x, y], [x, y], [x, y], [x, y]]
     */
    protected $coordinates;

    private $isLand = false;

    /**
     * @param Field $feild
     * @param Controller $controller
     * @param int $mm_sec
     */
    public function __construct(Field $feild, Controller $controller, $mm_sec)
    {
        $this->create_mm_sec = $mm_sec;
        $this->feild = $feild;
        $this->controller = $controller;
    }

    /**
     * 座標初期化
     *
     * @return array [[x, y], [x, y], [x, y], [x, y]]
     */
    abstract protected function initCoordinates($center);

    /**
     * 現在から回転処理させたときの座標を返す
     *
     * @return array [[x, y], [x, y], [x, y], [x, y]]
     */
    abstract protected function rotate();

    /**
     * タイルインスタンスを返す
     *
     * @return AbstractTile
     */
    abstract public function getTile();

    /**
     * 現在から動いたときの座標を返す
     *
     * @param boolean optional $is_left
     * @return array [[x, y], [x, y], [x, y], [x, y]]
     */
    protected function move($is_left = true)
    {
        $addtion_x = $is_left ? -1 : 1;

        $ret = [];
        foreach ($this->coordinates as $coordinate) {
            list($x, $y) = $coordinate;
            $ret[] = [$x + $addtion_x, $y];
        }
        return $ret;
    }

    /**
     * 現在から落下したときの座標を返す
     *
     * @param boolean optional $is_left
     * @return array [[x, y], [x, y], [x, y], [x, y]]
     */
    protected function fall()
    {
        $ret = [];
        foreach ($this->coordinates as $coordinate) {
            list($x, $y) = $coordinate;
            $ret[] = [$x, $y + 1];
        }
        return $ret;
    }

    /**
     * @return boolean
     */
    public function isLand()
    {
        return $this->isLand;
    }

    /**
     * 今のブロックのx, y座標配列を返す
     *
     * @return array [[x, y], [x, y], [x, y], [x, y]]
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetrice\FrameProcessInterface::frameProcess()
     */
    public function frameProcess($mm_sec)
    {
        if (is_null($this->coordinates)) {
            $this->coordinates = $this->initCoordinates(intval($this->feild->getWidth() / 2));

            if ($this->feild->isCollision($this->coordinates)) {
                throw new GameOverException();
            }

            $this->pre_move_mm_sec = $mm_sec;
            $this->pre_rotate_mm_sec = $mm_sec;
            $this->pre_fall_mm_sec = $mm_sec;

            $this->controller->clear();
            return;
        }

        // 横moveできるか
        if ($mm_sec - $this->pre_move_mm_sec >= 100) { // 連続移動を防止するために一定時間フレームをスキップ
            if (
                $this->controller->isInputLeft() ||
                $this->controller->isInputRight()
            ) {
                $moved = $this->move($this->controller->isInputLeft());
                if (! $this->feild->isCollision($moved)) {
                    $this->coordinates = $moved;
                }

                $this->pre_move_mm_sec = $mm_sec;
            }

            $this->controller->clear([
                Controller::LEFT,
                Controller::RIGHT
            ]);
        }


        // rotate処理
        if ($mm_sec - $this->pre_rotate_mm_sec >= 100) { // 連続移動を防止するために一定時間フレームをスキップ
            if ($this->controller->isInputRotateRight()) {
                $rotated = $this->rotate();
                if (! $this->feild->isCollision($rotated)) {
                    $this->coordinates = $rotated;
                }

                $this->pre_rotate_mm_sec = $mm_sec;
            }

            $this->controller->clear([
                Controller::ROTATE_LEFT,
                Controller::ROTATE_RIGHT
            ]);
        }

        // 落下処理（着地判定も）
        if ($mm_sec - $this->pre_fall_mm_sec >= min(self::$fallLevelMap)) { // 連続移動を防止するために一定時間フレームをスキップ
            // @todo 上が押されたときの即落下処理
            if(
                $this->controller->isInputDown() || // 下が押されたとき
                $mm_sec - $this->pre_fall_mm_sec >= self::$fallLevelMap[self::$level] // ゲームレベルに応じての自然落下
            ) {
                $falled = $this->fall();
                if ($this->feild->isCollision($falled)) {
                    $this->isLand = true;
                } else {
                    $this->coordinates = $falled;
                }

                $this->pre_fall_mm_sec = $mm_sec;
            }

            $this->controller->clear([
                Controller::DOWN,
                Controller::UP
            ]);
        }
    }
}
