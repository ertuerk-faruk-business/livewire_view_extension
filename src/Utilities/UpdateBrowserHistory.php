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
                if (is_bool($component->value)) {
                    $param[$component->id] = $component->toQueryable();
                } elseif (is_numeric($component->value)) {
                    $param[$component->id] = $component->toQueryable();
                } else {
                    $value = $component->toQueryable();
                    if (! empty($value)) {
                        $param[$component->id] = $value;
                    }
                }
            }
        }

        $view->emit('livewire_view_extension_url_changes', http_build_query($param));
    }
}
