<?php
namespace PruneMazui\Tetrice\Controller;

use PruneMazui\Tetrice\LoopProcessInterface;

abstract class AbstractController implements ControllerInterface, LoopProcessInterface
{
    const LEFT = 'left';
    const RIGHT = 'right';
    const DOWN = 'down';
    const UP = 'up';

    const ROTATE_RIGHT = 'rotate_right';
    const ROTATE_LEFT = 'rotate_left';

}