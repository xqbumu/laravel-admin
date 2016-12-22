<?php

namespace Encore\Incore\Grid\Filter\Field;

use Encore\Incore\Docore;

class DateTime
{
    /**
     * @var \Encore\Incore\Grid\Filter\AbstractFilter
     */
    protected $filter;

    public function __construct($filter)
    {
        $this->filter = $filter;

        $this->prepare();
    }

    public function prepare()
    {
        $options['format'] = 'YYYY-MM-DD HH:mm:ss';
        $options['locale'] = config('app.locale');

        $script = "$('#{$this->filter->getId()}').datetimepicker(".json_encode($options).');';

        Docore::script($script);
    }

    public function variables()
    {
        return [];
    }

    public function name()
    {
        return 'datetime';
    }
}
