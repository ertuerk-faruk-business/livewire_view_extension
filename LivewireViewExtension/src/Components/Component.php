<?php

namespace Livewire\ViewExtension\Components;
use Livewire\ViewExtension\View;

/**
 * This class just represent a component.
 * It is not a real livewire component.
 */
abstract class Component
{
    /**
     * Id must be unique.
     */
    public $id;

    /**
     * Component type.
     */
    public $type = 'component';

    /**
     * A component belongs to a view.
     */
    public $view;

    /**
     * The components value.
     */
    public $value;

    /**
     * Component class path.
     */
    public $class = Component::class;

    /**
     * All listeners.
     */
    public $listeners = [];

    /**
     * If true, the value will be saved into the session.
     */
    public $storable = false;

    /**
     * If true, query string functionalities will be copied.
     */
    public $queryable = false;

    /**
     * On component listener is triggered.
     */
    public function onTriggerListener(string $type, array $options)
    {
    }

    /**
     * Callback if this components converts to a array.
     */
    public function onArray(): array
    {
        return [];
    }

    public function __construct(View $view, string $id, array $listeners, bool $storable, bool $queryable, mixed $value)
    {
        $this->id = $id;
        $this->view = $view;
        $this->listeners = $listeners;
        $this->storable = $storable;
        $this->queryable = $queryable;
        $this->value = $value;
    }

    /**
     * Build component from array.
     */
    public static function inheritComponent(array $data, mixed $view, mixed $class)
    {
        return (new $class($view, $data['id'],$data['listeners'], $data['storable'], $data['queryable'], $data['value']));
    }

    /**
     * Updates component and call view.
     */
    public function updateComponent(array $data)
    {
        $this->view->updateComponent($this, $data);
    }

    /**
     * Updates component value.
     */
    public function update(mixed $value): self
    {
        $this->value = $value;

        $this->view->updateComponent($this, [
            'value' => $value,
        ]);

        return $this;
    }

    /**
     * Validates view.
     */
    public function validate(): void
    {
        $this->view->updatedData();
    }

    /**
     * Component has value or not.
     */
    public function hasValue(): bool
    {
        return ! is_null($this->value);
    }

    /**
     * Trigger component listener.
     */
    public function triggerListener(string $type, array $options = [
        'componentId' => null,
    ], View $view = null): void
    {
        if ($type == Listener::ComponentUpdated) {
            if ($this->storable) {
                $this->store($view);
            }
        }

        foreach ($this->listeners as $listener) {
            if ($listener['type'] == $type) {
                $action = $listener['callback'] ?? null;

                if (! empty($action)) {
                    $this->view->$action($this->id);
                } else {
                    $this->onTriggerListener($type, $options);
                }
            }
        }
    }

    /**
     * Stores data with component id to view.
     */
    public function store(mixed $view): self
    {
        $view->store($this->id, $this->value);

        return $this;
    }

    /**
     * Component as array.
     * Inherit to build a component.
     */
    public function toArray()
    {
        return array_merge([
            'id' => $this->id,
            'type' => $this->type,
            'listeners' => $this->listeners,
            'value' => $this->value,
            'storable' => $this->storable,
            'queryable' => $this->queryable,
            'class' => $this->class,
        ], $this->onArray());
    }
}
