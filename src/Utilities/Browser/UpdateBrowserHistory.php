<?php

namespace Livewire\ViewExtension\Utilities\Browser;

use Livewire\ViewExtension\View;

/** This class extends the query string functionality */
class UpdateBrowserHistory
{
    public array $parameters = [];

    public function __construct(View $view)
    {
        $param = [];

        if ($view->withComponents()) {
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
        }

        $param['view'] = $view->viewId;

        $this->parameters = $param;

        $view->emit('livewire_view_extension_url_changes', http_build_query($param));
    }
}