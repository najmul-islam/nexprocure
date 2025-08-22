<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TableToolbar extends Component
{
    public $exports;
    public $entriesOptions;
    public $currentEntries;
    public $searchValue;
    public $indexRoute;

    public function __construct($exports = [], $entriesOptions = [10, 25, 50, 100], $currentEntries = 10, $searchValue = '', $indexRoute = '')
    {
        $this->exports = $exports;
        $this->entriesOptions = $entriesOptions;
        $this->currentEntries = $currentEntries;
        $this->searchValue = $searchValue;
        $this->indexRoute = $indexRoute;
    }

    public function render()
    {
        return view('components.table-toolbar');
    }
}
