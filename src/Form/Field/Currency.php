<?php

namespace Encore\Incore\Form\Field;

use Encore\Incore\Form\Field;

class Currency extends Field
{
    protected $symbol = '$';

    protected static $js = [
        '/packages/docore/AdminLTE/plugins/input-mask/jquery.inputmask.js',
    ];

    public function symbol($symbol)
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function prepare($value)
    {
        return (float) $value;
    }

    public function render()
    {
        $this->script = <<<EOT

$('.{$this->getElementClass()}').inputmask("currency", {radixPoint: '.', prefix:'', removeMaskOnSubmit: true})

EOT;

        return parent::render()->with(['symbol' => $this->symbol]);
    }
}
