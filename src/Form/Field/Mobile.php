<?php

namespace Encore\Incore\Form\Field;

use Encore\Incore\Form\Field;

class Mobile extends Field
{
    protected static $js = [
        '/packages/docore/AdminLTE/plugins/input-mask/jquery.inputmask.js',
    ];

    protected $format = '99999999999';

    public function format($format)
    {
        $this->format = $format;

        return $this;
    }

    public function render()
    {
        $options = json_encode(['mask' => $this->format]);

        $this->script = <<<EOT

$('.{$this->getElementClass()}').inputmask($options);
EOT;

        return parent::render();
    }
}
