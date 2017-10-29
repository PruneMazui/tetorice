<?php
namespace PruneMazui\Tetrice;

class Timer implements LoopProcessInterface
{
    private $fps;

    private $flame_count = 0;

    private $start;

    private $frame_process;

    public function __construct(callable $frame_process, $fps = 30)
    {
        $this->frame_process = $frame_process;
        $this->fps = $fps;
    }

    private function execFrameProcess()
    {
        $this->flame_count++;

        $callback = $this->frame_process;
        $callback(intval((microtime(true) - $this->start) * 1000));
    }

    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetrice\LoopProcessInterface::loopProcess()
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
