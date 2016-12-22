<?php

namespace Encore\Incore\Form\Field;

class Year extends Date
{
    protected $format = 'YYYY';

    protected $view = 'docore::form.date';
}
