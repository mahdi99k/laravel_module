<?php

namespace App\View\Components;

use Illuminate\View\Component;

class File extends Component
{

    public $name, $placeholder, $value;

    public function __construct($name, $placeholder, $value = null)
    {
        $this->name = $name;
        $this->placeholder = $placeholder;
        $this->value = $value;
    }

    public function render()
    {
        return view('components.file');
    }
}
