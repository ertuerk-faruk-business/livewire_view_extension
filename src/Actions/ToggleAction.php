<?php

namespace Livewire\ViewExtension\Actions;

use Livewire\ViewExtension\Action;
use Livewire\ViewExtension\View;

/**
 * @param string ToggleId
 * @param bool validate
 */
class ToggleAction extends Action
{
    public function handle(View $view, array $data = []): mixed
    {
        $toggle = $view->getToggle($data[0])->toggle();

        $validate = $data[1] ?? false;

        if ($validate) {
            $toggle->validate();
        }

        return $toggle;
    }
}