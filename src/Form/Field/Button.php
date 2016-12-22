<?php

namespace Encore\Incore\Form\Field;

use Encore\Incore\Form\Field;

class Button extends Field
{
    protected $class = 'btn-primary';

    public function info()
    {
        $this->class = 'btn-info';

        return $this;
    }

    public function on($event, $callback)
    {
        $this->script = <<<EOT

        $('.{$this->getElementClass()}').on('$event', function() {
            $callback
        });

EOT;
    }
}
