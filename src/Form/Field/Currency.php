<?php

namespace Encore\Incore\Form\Field;

use Encore\Incore\Form\Field;

class Currency extends Field
{
    protected $symbol = '$';

    protected static $js = [
        '/packages/admin/AdminLTE/plugins/input-mask/jquery.inputmask.bundle.min.js',
    ];

    public function symbol($symbol)
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function prepare($value)
    {
        return (float) str_replace(',', '', $value);
    }

    public function render()
    {
        $this->script = <<<EOT

$('.{$this->getElementClass()}').inputmask("currency", {radixPoint: '.', prefix:''})

EOT;

        return parent::render()->with(['symbol' => $this->symbol]);
    }
}
