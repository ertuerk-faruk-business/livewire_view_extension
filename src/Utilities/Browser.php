<?php

namespace Livewire\ViewExtension\Utilities;

use Livewire\ViewExtension\Utilities\Browser\ClearBrowserHistory;
use Livewire\ViewExtension\Utilities\Browser\SetBrowserHistory;
use Livewire\ViewExtension\Utilities\Browser\UpdateBrowserHistory;
use Livewire\ViewExtension\View;

class Browser
{
    public static function set(View $view): array
    {
        $setBrowserHistory = new SetBrowserHistory($view);

        return $setBrowserHistory->parameters;
    }

    public static function update(View $view): array
    {
        $setBrowserHistory = new UpdateBrowserHistory($view);

        return $setBrowserHistory->parameters;
    }

    public static function clear(View $view)
    {
        new ClearBrowserHistory($view);
    }

    public static function merge(array $parameters, array $data): array
    {
        foreach ($parameters as $key => $parameter) {
            $parameters[$key] = $data[$key] ?? $parameter;
        }

        return $parameters;
    }
}