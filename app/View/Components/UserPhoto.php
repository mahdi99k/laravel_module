<?php

namespace App\View\Components;

use Illuminate\View\Component;

class UserPhoto extends Component
{

    public function __construct()
    {
        //
    }

    public function render()
    {
        return view('components.user-photo');
    }
}
