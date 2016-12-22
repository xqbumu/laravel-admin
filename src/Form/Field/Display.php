<?php

namespace Encore\Incore\Form\Field;

use Closure;
use Encore\Incore\Form\Field;

class Display extends Field
{
    protected $callback;

    public function format(Closure $callback)
    {
        $this->callback = $callback;
    }

    public function render()
    {
        if ($this->callback instanceof Closure) {
            $this->value = call_user_func($this->callback, $this->value);
        }

        return parent::render();
    }
}
