<?php

namespace Encore\Incore\Form\Field;

use Encore\Incore\Form\Field;

class Ip extends Field
{
    protected $rules = 'ip';

    protected static $js = [
        '/packages/docore/AdminLTE/plugins/input-mask/jquery.inputmask.js',
    ];

    public function render()
    {
        $this->script = '$("[data-mask]").inputmask();';

        return parent::render();
    }
}
