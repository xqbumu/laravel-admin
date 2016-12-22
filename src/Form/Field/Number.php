<?php

namespace Encore\Incore\Form\Field;

use Encore\Incore\Form\Field;

class Number extends Field
{
    protected static $js = [
        '/packages/docore/number-input/bootstrap-number-input.js',
    ];

    public function render()
    {
        $this->default(0);

        $this->script = <<<EOT

$('.{$this->getElementClass()}').bootstrapNumber({
	upClass: 'success',
	downClass: 'primary',
	center: true
});

EOT;

        return parent::render();
    }
}
