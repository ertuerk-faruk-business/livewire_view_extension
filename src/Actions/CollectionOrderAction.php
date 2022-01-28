<?php

namespace Livewire\ViewExtension\Actions;

use Livewire\ViewExtension\Action;
use Livewire\ViewExtension\View;

/**
 * @param string CollectionId
 * @param string OrderId
 * @param bool validate
 */
class CollectionOrderAction extends Action
{
    public function handle(View $view, array $data = []): mixed
    {
        $collection = $view->getCollection($data[0])->order($data[1]);

        $validate = $data[2] ?? false;

        if ($validate) {
            $collection->validate();
        }

        return $collection;
    }
}