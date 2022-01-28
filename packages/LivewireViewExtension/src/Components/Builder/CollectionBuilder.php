<?php

namespace Livewire\ViewExtension\Components\Builder;

use Livewire\ViewExtension\Components\Collection;
use Livewire\ViewExtension\Collection\Order;

class CollectionBuilder extends ComponentBuilder
{
    public array $orders = [];

    public function build(): Collection
    {
        parent::onBuild();

        $collection = Collection::inherit($this->toArray([
            'orders' => $this->orders,
        ]), $this->view);

        return $this->view->register($collection);
    }

    public function order(array | Order $orders): self
    {
        $result = [];

        if (! is_array($orders)) {
            $this->orders = [
                $orders->toArray(),
            ];

            return $this;
        }

        foreach ($orders as $order) {
            array_push($result, $order->toArray());
        }

        $this->orders = $result;

        return $this;
    }

    public function handleStorable()
    {
        $this->value = $this->view->getData($this->id, $this->value);
    }
}
