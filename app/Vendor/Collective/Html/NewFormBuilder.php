<?php

namespace App\Vendor\Collective\Html;

use Collective\Html\FormBuilder;

class NewFormBuilder extends FormBuilder{

    public function text($name, $value = null, $options = [])
    {
        $options['autocomplete'] = 'off';
        return $this->input('text', $name, $value, $options);
    }

}