<?php

namespace Livewire\ViewExtension\Utilities;

use Livewire\ViewExtension\View;

/** This class extends the query string functionality */
class UpdateBrowserHistory
{
    public function __construct(View $view)
    {
        $param = [];

        foreach($view->getAllComponents() as $component) {
            if ($component->queryable && $component->hasValue()) {
                $value = $component->toQueryable();
                if (! empty($value)) {
                    $param[$component->id] = $value;
                }
            }
        }

        $view->emit('lveUrlChanges', http_build_query($param));
    }
}
