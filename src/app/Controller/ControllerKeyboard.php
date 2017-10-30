<?php
namespace PruneMazui\Tetrice\Controller;

class ControllerKeyboard extends AbstractController
{
    private $key_map = [
        self::LEFT => ['1b5b44'],
        self::RIGHT => ['1b5b43'],
        self::DOWN => ['1b5b42'],
        self::UP => ['1b5b41'],

        self::ROTATE_RIGHT => ['20'],
    ];

    /**
     * 入力値バッファ
     * @var array
     */
    private $buffer = [];

    /**
     * 前回入力値バッファ
     * @var array
     */
    private $prebuffer = [];

    private $old_stty;

    private function readNonBlock(&$data) {
        $read = [STDIN];
        $write = [];
        $except = [];

        $result = stream_select($read, $write, $except, 0);

        if($result === false) {
            throw new \Exception('stream_select failed');
        }

        if($result === 0) {
            return false;
        }

        $data = stream_get_contents(STDIN);

        return true;
    }

    public function __construct(array $config = [])
    {
        $this->old_stty = shell_exec('stty -g');

        // キーボード入力時エンターを回避、入力内容を出力しない
        shell_exec("stty -icanon -echo");
        stream_set_blocking(STDIN, false);

        // @todo config
    }

    public function __destruct()
    {
        system('stty ' . $this->old_stty);
        stream_get_contents(STDIN);
    }

    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetrice\LoopProcessInterface::loopProcess()
     */
    public function loopProcess()
    {
        $data = "";
        if ($this->readNonBlock($data)) {
            $data = bin2hex($data);
            $this->buffer[$data]= $data;
        }
    }

    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetrice\Controller\ControllerInterface::clear()
     */
    public function clear($types = null)
    {
        if (is_null($types)) {
            $this->buffer = [];
            return;
        }

        if (! is_array($types)) {
            $types = [$types];
        }

        foreach ($types as $type) {
            if (! isset($this->key_map[$type])) {
                continue;
            }

            foreach ($this->key_map[$type] as $key) {
                unset($this->buffer[$key]);
            }
        }

    }

    private function isInput($key)
    {
        if (! isset($this->key_map[$key])) {
            return false;
        }

        foreach ($this->key_map[$key] as $needle) {
            if (isset($this->buffer[$needle])) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetrice\Controller\ControllerInterface::isInputLeft()
     */
    public function isInputLeft()
    {
        return $this->isInput(self::LEFT) && ! $this->isInput(self::RIGHT);
    }

    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetrice\Controller\ControllerInterface::isInputRight()
     */
    public function isInputRight()
    {
        return $this->isInput(self::RIGHT) && ! $this->isInput(self::LEFT);
    }

    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetrice\Controller\ControllerInterface::isInputDown()
     */
    public function isInputDown()
    {
        return $this->isInput(self::DOWN) && ! $this->isInput(self::UP);
    }

    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetrice\Controller\ControllerInterface::isInputUp()
     */
    public function isInputUp()
    {
        return $this->isInput(self::UP) && ! $this->isInput(self::DOWN);
    }

    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetrice\Controller\ControllerInterface::isInputRotateRight()
     */
    public function isInputRotateRight()
    {
        return $this->isInput(self::ROTATE_RIGHT) && ! $this->isInput(self::ROTATE_LEFT);
    }

    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetrice\Controller\ControllerInterface::isInputRotateLeft()
     */
    public function isInputRotateLeft()
    {
        return $this->isInput(self::ROTATE_LEFT) && ! $this->isInput(self::ROTATE_RIGHT);
    }

    /**
     * @return array
     */
    public function getBuffer()
    {
        return $this->buffer;
    }

    /**
     * @return string[]
     */
    public function getInputsArray()
    {
        $ret = [];

        foreach ($this->key_map as $name => $map) {
            foreach ($map as $needle) {
                if (isset($this->buffer[$needle])) {
                    $ret[$name] = $name;
                    break;
                }
            }
        }

        return $ret;
    }
}