<?php

namespace Livewire\ViewExtension\Components;

use Livewire\ViewExtension\View;
use Livewire\ViewExtension\Components\Builder\SelectorBuilder;

/**
 * This class represents a component.
 */
class Selector extends Component
{
    public $type = 'selector';

    public $class = Selector::class;

    //** Custom Attributes */

    public $selectables;

    public static function create(View $view, string $id): SelectorBuilder
    {
        return new SelectorBuilder($view, $id);
    }

    public static function inherit(array $data, mixed $view): self
    {
        $selector = self::inheritComponent($data, $view, Selector::class);
        $selector->selectables = $data['selectables'];

        return $selector;
    }

    public function updateSelectables(mixed $selectables = null, bool $validate = true)
    {
        $this->selectables = $selectables ?? $this->selectables;

        $this->updateComponent([
            'selectables' => $selectables,
        ], $validate);
    }

    /** Returns all selected items */
    public function items(): array
    {
        $result = [];

        foreach ($this->value as $selectedItemId) {
            foreach ($this->selectables as $selectable) {
                if ($selectable['id'] == $selectedItemId) {
                    array_push($result, $selectable);
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * Select value with given id.
     */
    public function add(string $id, string $key = 'id'): self
    {
        $selectedItemId = null;

        foreach($this->selectables as $item) {
            if ($id == $item[$key]) {
                $selectedItemId = $item[$key];
                break;
            }
        }

        $values = $this->value ?? [];

        array_push($values, $selectedItemId);

        $this->update($values);

        return $this;
    }

    public function select(string $id, string $key = 'id'): self
    {
        $selectedItemId = null;

        foreach($this->selectables as $item) {
            if ($id == $item[$key]) {
                $selectedItemId = $item[$key];
                break;
            }
        }
        $values = [];

        array_push($values, $selectedItemId);

        $this->update($values);

        return $this;
    }

    /**
     * Unselect value.
     */
    public function remove(string $id): self
    {
        $values = [];

        foreach ($this->value as $item) {
            if ($item != $id) {
                array_push($values, $item);
            }
        }

        $this->update($values);

        return $this;
    }

    /**
     * Selector has value with given id.
     */
    public function has(string $id): bool
    {
        if (empty($this->value)) {
            return false;
        }

        foreach($this->value as $item) {
            if ($id == $item) {
                return true;
            }
        }

        return false;
    }

    public function firstSelected(): mixed
    {
        $id = $this->first();

        foreach ($this->selectables as $selectable) {
            if ($selectable['id'] == $id) {
                return $selectable;
            }
        }

        return null;
    }

    public function first(): mixed
    {
        if (is_array($this->value)) {
            if (empty($this->value)) {
                return null;
            }

            return $this->value[0];
        }

        return $this->value;
    }

    public function toggle(string $id): self
    {
        if ($this->has($id)) {
            $this->remove($id);
        } else {
            $this->add($id);
        }

        return $this;
    }

    public function toQueryable(): string
    {
        return implode('-', $this->value);
    }

    public function onArray(): array
    {
        return [
            'selectables' => $this->selectables,
        ];
    }
}