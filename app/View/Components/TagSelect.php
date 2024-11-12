<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TagSelect extends Component
{

    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function render()
    {
        return view('components.tag-select');
    }
}
