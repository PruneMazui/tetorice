<?php
namespace PruneMazui\Tetrice;

interface FrameProcessInterface
{
    /**
     * １フレームにおける処理
     *
     * @param int $mm_sec 
     */
    public function frameProcess($mm_sec);
}
