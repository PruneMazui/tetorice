<?php
namespace PruneMazui\Tetrice\Controller;

interface ControllerInterface
{
    /**
     * 左が入力されているか
     * @return boolean
     */
    public function isInputLeft();

    /**
     * 右が入力されているか
     * @return boolean
     */
    public function isInputRight();

    /**
     * 下が入力されているか
     * @return boolean
     */
    public function isInputDown();

    /**
     * 上が入力されているか
     * @return boolean
     */
    public function isInputUp();

    /**
     * 右回転が入力されているか
     * @return boolean
     */
    public function isInputRotateRight();

    /**
     * 左回転が入力されているか
     * @return boolean
     */
    public function isInputRotateLeft();

    /**
     * 入力バッファをクリア
     * @param string | array optional $types
     */
    public function clear($types = null);
}