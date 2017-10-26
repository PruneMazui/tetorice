<?php
namespace PruneMazui\Tetrice;

class Timer implements LoopProcessInterface
{
    private $fps = 15;

    private $flame_count = 0;

    private $start;

    private $frame_process;

    public function __construct(callable $frame_process)
    {
        $this->frame_process = $frame_process;
        ob_start();
    }

    private function execFrameProcess()
    {
        // ちらつき防止のためあえてここで flush
        ob_flush();
        flush();

        $this->flame_count++;
        ($this->frame_process)(intval((microtime(true) - $this->start) * 1000));
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
