<?php

namespace Livewire\ViewExtension;

use Livewire\ViewExtension\View;
use Livewire\ViewExtension\Components\Collection;

abstract class Query
{
    /**
     * Convert your query here.
     */
    public abstract function convert(mixed $data);

    /**
     * Run your query here.
     */
    public abstract function get(View $view, Collection $collection);
}
