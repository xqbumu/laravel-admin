<?php

namespace Encore\Incore\Form\Field;

class Month extends Date
{
    protected $format = 'MM';

    protected $view = 'docore::form.date';
}
