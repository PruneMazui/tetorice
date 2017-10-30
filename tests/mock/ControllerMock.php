<?php
namespace PruneMazui\Tetrice\Tests;

use PruneMazui\Tetrice\Controller\Controller;

class ControllerMock extends Controller
{
    private $inputs = [];

    public function setInputs($inputs)
    {
        if (!is_array($inputs)) {
            $inputs = [$inputs];
        }

        $this->inputs = $inputs;
        return $this;
    }

    public function loopProcess()
    {
        // noop
    }

    public function isInputLeft()
    {
        return in_array(Controller::LEFT, $this->inputs);
    }

    public function isInputRight()
    {
        return in_array(Controller::RIGHT, $this->inputs);
    }

    public function isInputDown()
    {
        return in_array(Controller::DOWN, $this->inputs);
    }

    public function isInputUp()
    {
        return in_array(Controller::UP, $this->inputs);
    }

    public function isInputRotateRight()
    {
        return in_array(Controller::ROTATE_RIGHT, $this->inputs);
    }

    public function isInputRotateLeft()
    {
        return in_array(Controller::ROTATE_LEFT, $this->inputs);
    }

    public function clear($types = null)
    {
        if (is_null($types)) {
            $this->inputs = [];
        } else {
            if (!is_array($types)) {
                $types = [$types];
            }

            foreach ($types as $type) {
                foreach ($this->inputs as $key => $input) {
                    if ($input == $type) {
                        unset($this->inputs[$key]);
                    }
                }
            }
        }
    }
}