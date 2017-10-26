<?php
namespace PruneMazui\Tetris;

class Timer implements LoopProcessInterface
{
    private $fps = 30;

    private $flame_count = 0;

    private $start;

    private $frame_process;

    public function __construct(callable $frame_process)
    {
        $this->frame_process = $frame_process;
    }

    private function execFrameProcess()
    {
        $this->flame_count++;
        ($this->frame_process)(intval((microtime(true) - $this->start) * 1000));
    }

    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetris\LoopProcessInterface::loopProcess()
     */
    public function loopProcess()
    {
        if (is_null($this->start)) {
            $this->start = microtime(true);
            return $this->execFrameProcess();
        }

        $next_time = ((1 / $this->fps) * $this->flame_count) + $this->start;

        if (microtime(true) >= (((1 / $this->fps) * $this->flame_count) + $this->start)) {
            return $this->execFrameProcess();
        }
    }
}
