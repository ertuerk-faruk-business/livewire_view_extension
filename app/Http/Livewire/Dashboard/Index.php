<?php

namespace App\Http\Livewire\Dashboard;

use Laravel\Cms\Cms;
use Laravel\Cms\Models\Item;
use Laravel\Cms\Models\Product;
use Livewire\ViewExtension\InteractableView;
use Laravel\Cms\Models\ProductBlueprint;
use Laravel\Cms\Models\ProductBlueprintRelation;
use Laravel\Cms\Services\Product\Relation\Query;
use Livewire\ViewExtension\Components\Collection;

class Index extends InteractableView
{
    public $viewId = 'dashboard.index';

    public function onMount(mixed $data)
    {
        $employees = Cms::productBlueprint('employee')->products->map(function ($employee) {
            return $employee->data()['translated'];
        })->all();

        $art = Cms::productBlueprint('art')->products->map(function ($art) {
            return $art->data()['translated'];
        })->all();

        Collection::create($this, 'employees')->value($employees)->build();
        Collection::create($this, 'art')->value($art)->build();

        return [];
    }
}
