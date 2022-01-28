<?php

namespace Livewire\ViewExtension\Actions;

use Livewire\ViewExtension\Action;
use Livewire\ViewExtension\View;

/**
 * @param string SelectorId
 * @param string SelectableId
 * @param bool validate
 */
class SelectAction extends Action
{
    public function handle(View $view, array $data = []): mixed
    {
        $selector = $view->getSelector($data[0])->select($data[1]);

        $validate = $data[2] ?? false;

        if ($validate) {
            $selector->validate();
        }

        return $selector;
    }
}