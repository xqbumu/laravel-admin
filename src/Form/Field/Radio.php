<?php

namespace Encore\Incore\Form\Field;

use Encore\Incore\Form\Field;
use Illuminate\Contracts\Support\Arrayable;

class Radio extends Field
{
    protected static $css = [
        '/packages/docore/AdminLTE/plugins/iCheck/all.css',
    ];

    protected static $js = [
        'packages/docore/AdminLTE/plugins/iCheck/icheck.min.js',
    ];

    /**
     * Set options.
     *
     * @param array|callable|string $options
     *
     * @return $this
     */
    public function options($options = [])
    {
        if ($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        $this->options = (array) $options;

        return $this;
    }

    /**
     * Set options.
     *
     * @param array|callable|string $values
     *
     * @return $this
     */
    public function values($values)
    {
        return $this->options($values);
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->script = "$('.{$this->getElementClass()}').iCheck({radioClass:'iradio_minimal-blue'});";

        return parent::render()->with(['options' => $this->options]);
    }
}
