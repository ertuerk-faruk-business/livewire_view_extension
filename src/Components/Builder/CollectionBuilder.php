<?php

namespace Livewire\ViewExtension\Components\Builder;

use Livewire\ViewExtension\Components\Collection;
use Livewire\ViewExtension\Collection\Order;

class CollectionBuilder extends ComponentBuilder
{
    public array $orders = [];

    public ?string $currentOrder = null;

    public function build(): Collection
    {
        parent::onBuild();

        $collection = Collection::inherit($this->toArray([
            'orders' => $this->orders,
            'currentOrder' => $this->currentOrder,
        ]), $this->view);

        return $this->view->register($collection);
    }

    public function order(array | Order $orders, ?string $default = null): self
    {
        $result = [];

        if (! is_array($orders)) {
            $this->orders = [
                $orders->toArray(),
            ];

            return $this;
        }

        foreach ($orders as $order) {
            if (! is_array($order)) {
                array_push($result, $order->toArray());
            } else {
                array_push($result, $order);
            }
        }

        $this->orders = $result;

        $this->currentOrder = $default;

        return $this;
    }

    public function handleStorable()
    {
        $this->value = $this->view->getData($this->id, $this->value);
    }
}
