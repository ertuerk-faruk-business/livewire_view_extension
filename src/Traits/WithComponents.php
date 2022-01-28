<?php

namespace Livewire\ViewExtension\Traits;

use Livewire\ViewExtension\Components\Input;
use Livewire\ViewExtension\Components\Calendar;
use Livewire\ViewExtension\Components\Collection;
use Livewire\ViewExtension\Components\Selector;
use Livewire\ViewExtension\Components\Toggle;
use Livewire\ViewExtension\Components\Component;
use Livewire\ViewExtension\Components\Listener;
use Livewire\ViewExtension\Utilities\Browser;
use Livewire\ViewExtension\Utilities\Changes;
use Livewire\ViewExtension\Utilities\UpdateBrowserHistory;

/**
 * All component interfaces are defined here.
 * Use this trait, if you want to use components in your view.
 * You can expand this class as you wish.
 */
trait WithComponents
{
    protected Changes $changes;

    /**
     * Return all changes from last update.
     */
    public function changes(): Changes
    {
        return $this->changes;
    }

    public function allChanges(): Changes
    {
        return new Changes($this->mountData, $this->data, $this);
    }

    /**
     * Updates component data for this view.
     */
    public function updateComponent(mixed $component, array $data)
    {
        foreach ($data as $key => $value) {
            $this->data['components'][$component->id][$key] = $value;
        }
    }

    public function register(Component $component): mixed
    {
        $this->data['components'][$component->id] = $component->toArray();

        return $this->getComponent($component->id, $component->class);
    }

    public function getComponent(string $id, mixed $class): Component | null
    {
        if (!array_key_exists($id, $this->data['components'])) {
            return null;
        }

        return $class::inherit($this->data['components'][$id], $this);
    }

    /**
     * Returns selector as array.
     */
    public function getSelector(string $id): Selector | null
    {
        return $this->getComponent($id, Selector::class);
    }

    /**
     * Returns input.
     */
    public function getInput(string $id): Input | null
    {
        return $this->getComponent($id, Input::class);
    }

    /**
     * Returns calendar.
     */
    public function getCalendar(string $id): Calendar | null
    {
        return $this->getComponent($id, Calendar::class);
    }

    /**
     * Returns toggle.
     */
    public function getToggle(string $id): Toggle | null
    {
        return $this->getComponent($id, Toggle::class);
    }

    /**
     * Returns collection.
     */
    public function getCollection(string $id): Collection | null
    {
        return $this->getComponent($id, Collection::class);
    }

    /**
     * Return all components.
     */
    public function getAllComponents(): array
    {
        $result = [];

        if (!array_key_exists('components', $this->data)) {
            return $result;
        }

        foreach ($this->data['components'] as $key => $value) {
            $component = $this->getComponent($key, $value['class']);
            if (!empty($component)) {
                array_push($result, $component);
            }
        }

        return $result;
    }

    public function handleUpdatedData()
    {
        $this->changes = new Changes($this->oldData, $this->data, $this);

        foreach ($this->changes()->getAllComponents() as $change) {
            $change->triggerListener(Listener::ComponentUpdated, [], $this);
        }

        foreach ($this->getAllComponents() as $component) {
            foreach ($this->changes()->getAllComponents() as $change) {
                $component->triggerListener(Listener::OtherComponentUpdated, [
                    'componentId' => $change->id,
                ]);
            }
        }

        $this->httpParameters = Browser::update($this);
    }

    public function withComponents(): bool
    {
        return true;
    }
}
