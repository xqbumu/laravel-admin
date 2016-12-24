<?php

namespace Encore\Incore\Form\Field;

use Encore\Incore\Form\Field;

class Icon extends Field
{
    protected static $css = [
        '/packages/docore/fontawesome-iconpicker/dist/css/fontawesome-iconpicker.min.css',
    ];

    protected static $js = [
        '/packages/docore/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.min.js',
    ];

    public function render()
    {
        $this->script = <<<EOT

$('.{$this->getElementClass()}').iconpicker();

EOT;

        return parent::render();
    }
}
