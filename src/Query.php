<?php

namespace Livewire\ViewExtension;

use Livewire\ViewExtension\Collection\Order;
use Livewire\ViewExtension\View;
use Livewire\ViewExtension\Components\Collection;

abstract class Query
{
    /**
     * Run your query here.
     */
    public abstract function get(View $view, Collection $collection, ?Order $order);
}
