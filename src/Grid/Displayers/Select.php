<?php

namespace Encore\Incore\Grid\Displayers;

use Encore\Incore\Docore

class Select extends AbstractDisplayer
{
    public function display($options = [])
    {
        $name = $this->column->getName();

        $class = "grid-select-{$name}";
        $token = csrf_token();

        $script = <<<EOT

$('.$class').select2().on('change', function(){

    var pk = $(this).data('key');
    var value = $(this).val();

    $.ajax({
        url: "/{$this->grid->resource()}/" + pk,
        type: "POST",
        data: {
            $name: value,
            _token: '$token',
            _method: 'PUT'
        },
        success: function (data) {
            console.log(data);
        }
    });
});

EOT;

        Docore::script($script);

        $key = $this->row->{$this->grid->getKeyName()};

        $optionsHtml = '';

        foreach ($options as $option => $text) {
            $selected = $option == $this->value ? 'selected' : '';
            $optionsHtml .= "<option value=\"$option\" $selected>$text</option>";
        }

        return <<<EOT
<select style="width: 100%;" class="$class btn btn-mini" data-key="$key">
$optionsHtml
</select>

EOT;
    }
}
