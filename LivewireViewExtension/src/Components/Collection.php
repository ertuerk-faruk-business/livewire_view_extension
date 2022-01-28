<?php

namespace Livewire\ViewExtension\Components;

use Livewire\ViewExtension\Collection\Order;
use Livewire\ViewExtension\Components\Builder\CollectionBuilder;
use Livewire\ViewExtension\View;

/**
 * This class represents a component.
 */
class Collection extends Component
{
    public $type = 'collection';

    public $class = Collection::class;

    //** Custom Attributes */

    public $orders = [];

    public ?string $currentOrder = null;

    public $query = null;

    public static function create(View $view, string $id): CollectionBuilder
    {
        return new CollectionBuilder($view, $id);
    }

    public static function inherit(array $data, mixed $view): self
    {
        $collection = self::inheritComponent($data, $view, Collection::class);
        $collection->query = $data['query'] ?? null;
        $collection->orders = $data['orders'] ?? [];

        if (! empty($collection->orders)) {
            $collection->currentOrder = $data['currentOrder'] ?? $data['orders'][0]['id'];
        }

        return $collection;
    }

    public function onTriggerListener(string $type, array $options)
    {
        if ($type == Listener::OtherComponentUpdated) {
            $this->query(null);
        }
    }

    /**
     * Return value with limited length.
     */
    public function limit(?int $count = null): mixed
    {
        if (is_null($count)) {
            return $this->value;
        }

        $result = [];

        $counter = 0;

        foreach ($this->value as $key => $value) {
            if ($counter >= $count) {
                break;
            }

            $result[$key] = $value;
            $counter++;
        }

        return $result;
    }

    public function order(string $id): self
    {
        if (empty($this->orders)) {
            return $this;
        }

        $this->currentOrder = $id;

        return $this->query();
    }

    public function getOrder(?string $id): Order | null
    {
        if (empty($this->orders)) {
            return null;
        }

        if (empty($id)) {
            return Order::parse($this->orders[0]);
        }

        foreach ($this->orders as $order) {
            if ($order['id'] == $id) {

                return Order::parse($order);
            }
        }

        return null;
    }

    /**
     * Set the Query for this Collection.
     */
    public function query(mixed $query = null): self
    {
        if (! empty($query)) {
            $this->query = $query;
        }

        if (empty($this->query)) {

            return $this;
        }

        $queryObject = eval("return new {$this->query};");

        $this->value = $queryObject->get($this->view, $this, $this->getOrder($this->currentOrder));

        $this->updateComponent($this->toArray());

        return $this;
    }

    public function count(): int
    {
        if (! $this->hasValue()) {

            return 0;
        }

        return count($this->value);
    }

    public function isEmpty(): bool
    {
        return $this->count() == 0;
    }

    public function onArray(): array
    {
        return [
            'orders' => $this->orders,
            'query' => $this->query,
        ];
    }
}