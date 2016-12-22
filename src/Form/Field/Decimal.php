<?php

namespace Encore\Incore\Form\Field;

use Encore\Incore\Form\Field;

class Decimal extends Field
{
    protected static $js = [
        '/packages/docore/AdminLTE/plugins/input-mask/jquery.inputmask.js',
    ];

    public function render()
    {
        $this->script = "$('.{$this->getElementClass()}').inputmask('decimal', {
    rightAlign: true
  });";

        return parent::render();
    }
}
