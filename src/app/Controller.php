<?php
namespace PruneMazui\Tetrice;

class Controller implements LoopProcessInterface
{
    const LEFT = 'left';
    const RIGHT = 'right';
    const DOWN = 'down';
    const UP = 'up';

    const ROTATE_RIGHT = 'rotate_right';
    const ROTATE_LEFT = 'rotate_left';

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
     * 入力バッファをクリア
     */
    public function clear()
    {
        $this->prebuffer = $this->buffer;
        $this->buffer = [];
    }

    private function isInput($key, $is_previous = false)
    {
        if (! isset($this->key_map[$key])) {
            return false;
        }

        $buffer = $is_previous ? $this->prebuffer : $this->buffer;

        foreach ($this->key_map[$key] as $needle) {
            if (isset($buffer[$needle])) {
                return true;
            }
        }

        return false;
    }

    /**
     * 左が入力されているか
     * @return boolean
     */
    public function isInputLeft()
    {
        return $this->isInput(self::LEFT) && ! $this->isInput(self::RIGHT);
    }

    /**
     * 右が入力されているか
     * @return boolean
     */
    public function isInputRight()
    {
        return $this->isInput(self::RIGHT) && ! $this->isInput(self::LEFT);
    }

    /**
     * 下が入力されているか
     * @return boolean
     */
    public function isInputDown()
    {
        return $this->isInput(self::DOWN) && ! $this->isInput(self::UP);
    }

    /**
     * 上が入力されているか
     * @return boolean
     */
    public function isInputUp()
    {
        return $this->isInput(self::UP) && ! $this->isInput(self::DOWN);
    }

    /**
     * 右回転が入力されているか
     * @return boolean
     */
    public function isInputRotateRight()
    {
        // 前回入力されているときは無視
        return $this->isInput(self::ROTATE_RIGHT) && ! $this->isInput(self::ROTATE_LEFT) &&
            !($this->isInput(self::ROTATE_RIGHT, true) && ! $this->isInput(self::ROTATE_LEFT, true));
    }

    /**
     * 左回転が入力されているか
     * @return boolean
     */
    public function isInputRotateLeft()
    {
        return $this->isInput(self::ROTATE_LEFT) && ! $this->isInput(self::ROTATE_RIGHT) &&
            !($this->isInput(self::ROTATE_LEFT, true) && ! $this->isInput(self::ROTATE_RIGHT, true));
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