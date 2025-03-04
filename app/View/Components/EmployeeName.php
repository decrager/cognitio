<?php

namespace App\View\Components;

use Illuminate\View\Component;

class EmployeeName extends Component
{
    public $id;
    public $name;

    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function render()
    {
        return view('components.employee-name');
    }
}
