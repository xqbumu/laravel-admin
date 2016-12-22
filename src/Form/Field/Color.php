<?php

namespace Encore\Incore\Form\Field;

use Encore\Incore\Form\Field;

class Color extends Field
{
    protected static $css = [
        '/packages/docore/AdminLTE/plugins/colorpicker/bootstrap-colorpicker.min.css',
    ];

    protected static $js = [
        '/packages/docore/AdminLTE/plugins/colorpicker/bootstrap-colorpicker.min.js',
    ];

    /**
     * Use `hex` format.
     *
     * @return $this
     */
    public function hex()
    {
        return $this->options(['format' => 'hex']);
    }

    /**
     * Use `rgb` format.
     *
     * @return $this
     */
    public function rgb()
    {
        return $this->options(['format' => 'rgb']);
    }

    /**
     * Use `rgba` format.
     *
     * @return $this
     */
    public function rgba()
    {
        return $this->options(['format' => 'rgba']);
    }

    /**
     * Render this filed.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $options = json_encode($this->options);

        $this->script = "$('.{$this->getElementClass()}').colorpicker($options);";

        return parent::render();
    }
}
