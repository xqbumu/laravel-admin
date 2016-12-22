<?php

namespace Encore\Incore\Form\Field;

use Encore\Incore\Form\Field;

class Slider extends Field
{
    protected static $css = [
        '/packages/docore/AdminLTE/plugins/ionslider/ion.rangeSlider.css',
        '/packages/docore/AdminLTE/plugins/ionslider/ion.rangeSlider.skinNice.css',
    ];

    protected static $js = [
        '/packages/docore/AdminLTE/plugins/ionslider/ion.rangeSlider.min.js',
    ];

    protected $options = [
        'type'     => 'single',
        'prettify' => false,
        'hasGrid'  => true,
    ];

    public function render()
    {
        $option = json_encode($this->options);

        $this->script = "$('.{$this->getElementClass()}').ionRangeSlider($option)";

        return parent::render();
    }
}
