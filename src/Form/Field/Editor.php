<?php

namespace Encore\Incore\Form\Field;

use Encore\Incore\Form\Field;

class Editor extends Field
{
    protected static $js = [
        '/packages/incore/AdminLTE/plugins/ckeditor/ckeditor.js',
    ];

    public function render()
    {
        $this->script = "CKEDITOR.replace('{$this->column}');";

        return parent::render();
    }
}
