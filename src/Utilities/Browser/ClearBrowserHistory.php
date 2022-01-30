<?php

namespace Livewire\ViewExtension\Utilities\Browser;

use Livewire\ViewExtension\View;

/** This class extends the query string functionality */
class ClearBrowserHistory
{
    public function __construct(View $view)
    {
        $view->emit('livewire_view_extension_url_changes', http_build_query([]));
    }
}