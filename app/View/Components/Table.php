<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Table extends Component
{
    public $data;        // Collection of rows
    public $columns;     // Array of columns with keys and labels
    public $actions;     // Array of actions for each row
    public $sortField;
    public $sortOrder;
    public $routePrefix; // For sortable links

    /**
     * Create a new component instance.
     */
    public function __construct($data, $columns, $actions = [], $sortField = null, $sortOrder = 'asc', $routePrefix = '')
    {
        $this->data = $data;
        $this->columns = $columns;
        $this->actions = $actions;
        $this->sortField = $sortField;
        $this->sortOrder = $sortOrder;
        $this->routePrefix = $routePrefix;
    }

    public function render()
    {
        return view('components.table');
    }
}
