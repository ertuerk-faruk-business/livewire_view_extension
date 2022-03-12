<?php

namespace Livewire\ViewExtension\Utilities;

use Livewire\ViewExtension\Components\Action;
use Livewire\ViewExtension\Components\Calendar;
use Livewire\ViewExtension\Components\Collection;
use Livewire\ViewExtension\Components\Input;
use Livewire\ViewExtension\Components\Selector;
use Livewire\ViewExtension\Components\Toggle;
use Livewire\ViewExtension\Components\Component;

class Changes
{
    public array $changes = [];

    public array $deletions = [];

    public array $additions = [];

    public array $data;

    public $page;

    function __construct(array $oldData, array $newData, mixed $page)
    {
        $this->page = $page;
        $this->data = $newData;

        $this->compareComponent($oldData, $newData);
    }

    /**
     * Compare two arrays as components.
     */
    public function compareComponent(array $oldData, array $newData)
    {
        if (!array_key_exists('components', $oldData)) {
            $oldData['components'] = [];
        }

        if (!array_key_exists('components', $newData)) {
            $newData['components'] = [];
        }

        $changes = [];
        $deletions = [];
        $additions = [];

        foreach ($oldData['components'] as $key => $value) {
            if (!array_key_exists($key, $newData['components'])) {
                array_push($deletions, $key);
                continue;
            }

            if ($value['value'] != $newData['components'][$key]['value']) {
                array_push($changes, $key);
            }
        }

        foreach ($newData['components'] as $key => $value) {
            if (!array_key_exists($key, $oldData['components'])) {
                array_push($additions, $key);
                continue;
            }
        }

        $this->additions['components'] = $additions;
        $this->deletions['components'] = $deletions;
        $this->changes['components'] = $changes;
    }

    /**
     * Returns input added or changed.
     */
    public function getInput(string $id): Input | null
    {
        return $this->getComponent($id, Input::class);
    }

    /**
     * Returns calendar added or changed.
     */
    public function getCalendar(string $id): Calendar | null
    {
        return $this->getComponent($id, Calendar::class);
    }

    /**
     * Returns collection added or changed.
     */
    public function getCollection(string $id): Collection | null
    {
        return $this->getComponent($id, Collection::class);
    }

    /**
     * Returns selector added or changed.
     */
    public function getSelector(string $id): Selector | null
    {
        return $this->getComponent($id, Selector::class);
    }

    /**
     * Returns toggle added or changed.
     */
    public function getToggle(string $id): Toggle | null
    {
        return $this->getComponent($id, Toggle::class);
    }

    public function getComponent(string $id, mixed $class): Component | null
    {
        if (!array_key_exists($id, $this->data['components'] ?? [])) {
            return null;
        }

        if (in_array($id, $this->deletions['components'])) {
            return null;
        }

        if (!in_array($id, $this->additions['components']) && !in_array($id, $this->changes['components'])) {
            return null;
        }

        return $class::inherit($this->data['components'][$id], $this->page);
    }

    public function getAllComponents(): array
    {
        $result = [];

        if (!array_key_exists('components', $this->data)) {
            return $result;
        }

        foreach ($this->data['components'] as $key => $value) {
            if (empty($value['class'] ?? null)) {
                continue;
            }

            $component = $this->getComponent($key, $value['class']);
            if (! empty($component)) {
                array_push($result, $component);
            }
        }

        return $result;
    }

    /**
     * Is something different or not.
     */
    public function isDifferent(): bool
    {
        return ($this->hasChanges() || $this->hasDeletions() || $this->hasAdditions());
    }

    /**
     * Has any changes?
     */
    public function hasChanges(): bool
    {
        return !empty($this->changes);
    }

    /**
     * Has any additions?
     */
    public function hasAdditions(): bool
    {
        return !empty($this->additions);
    }

    /**
     * Has any deletions?
     */
    public function hasDeletions(): bool
    {
        return !empty($this->deletions);
    }
}
