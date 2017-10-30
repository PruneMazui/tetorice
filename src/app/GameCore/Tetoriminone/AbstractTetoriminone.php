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

    protected $field;

    protected $controller;

    private $create_mm_sec;

    private $pre_fall_mm_sec;
    private $pre_move_mm_sec;
    private $pre_rotate_mm_sec;

    private $isLand = false;

    /**
     * @var array [[x, y], [x, y], [x, y], [x, y]]
     */
    protected $coordinates;


    /**
     * @param Field $field
     * @param Controller $controller
     * @param int $mm_sec
     */
    public function __construct(Field $field, Controller $controller, $mm_sec)
    {
        $this->create_mm_sec = $mm_sec;
        $this->field = $field;
        $this->controller = $controller;
    }

    /**
     * 初期座標を返す
     * @param int $center
     * @return array [[x, y], [x, y], [x, y], [x, y]]
     */
    abstract protected function getInitialCoordinates($center);


    /**
     * 現在位置から回転
     *
     * @return bool
     */
    abstract protected function rotate(Field $field);

    /**
     * タイルインスタンスを返す
     *
     * @return AbstractTile
     */
    abstract public function getTile();

    /**
     * 座標初期化
     *
     * @param Field $field
     * @return bool
     */
    protected function initCoordinates(Field $field)
    {
        $initial = $this->getInitialCoordinates($field->getHorizontalCenter());
        if ($field->isCollision($initial)) {
            return false;
        }

        $this->coordinates = $initial;
        return true;
    }


    /**
     * 現在位置から動く
     *
     * @param Field $field
     * @param boolean optional $is_left
     * @return bool
     */
    protected function move(Field $field, $is_left = true)
    {
        $addtion_x = $is_left ? -1 : 1;

        $next = [];
        foreach ($this->coordinates as $coordinate) {
            list($x, $y) = $coordinate;
            $next[] = [$x + $addtion_x, $y];
        }

        if ($field->isCollision($next)) {
            return false;
        }

        $this->coordinates = $next;
        return true;
    }

    /**
     * 現在位置から落下
     *
     * @param Field $field
     * @return boolean
     */
    protected function fall(Field $field)
    {
        $next = [];
        foreach ($this->coordinates as $coordinate) {
            list($x, $y) = $coordinate;
            $next[] = [$x, $y + 1];
        }

        if ($field->isCollision($next)) {
            return false;
        }

        $this->coordinates = $next;
        return true;
    }

    /**
     * 着地しているか
     *
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

            if (! $this->initCoordinates($this->field)) {
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
                $this->move($this->field, $this->controller->isInputLeft());
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
                $this->rotate($this->field);
                $this->pre_rotate_mm_sec = $mm_sec;
            }

            $this->controller->clear([
                Controller::ROTATE_LEFT,
                Controller::ROTATE_RIGHT
            ]);
        }

        // 落下処理（着地判定も）
        if ($mm_sec - $this->pre_fall_mm_sec >= min(self::$fallLevelMap)) { // 連続移動を防止するために一定時間フレームをスキップ
            if($this->controller->isInputUp()) {
                // 失敗するまで繰り返す
                while ($this->fall($this->field)) {}
                $this->pre_fall_mm_sec = $mm_sec;
                $this->isLand = true;
            } else if(
                $this->controller->isInputDown() || // 下が押されたとき
                $mm_sec - $this->pre_fall_mm_sec >= self::$fallLevelMap[self::$level] // ゲームレベルに応じての自然落下
            ) {
                // 落下失敗したら着地とみなす
                if (! $this->fall($this->field)) {
                    $this->isLand = true;
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
