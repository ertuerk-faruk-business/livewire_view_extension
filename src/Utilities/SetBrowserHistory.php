<?php

namespace Livewire\ViewExtension\Utilities;

use Livewire\ViewExtension\View;

/** This class extends the query string functionality */
class SetBrowserHistory
{
    public array $parameters = [];

    public function __construct(View $view)
    {
        $param = $view->httpParameters;

        $param['view'] = $view->viewId;

        $this->parameters = $param;

        $view->emit('livewire_view_extension_url_changes', http_build_query($param));
    }
}