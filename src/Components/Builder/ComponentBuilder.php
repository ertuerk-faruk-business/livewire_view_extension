<?php

namespace Livewire\ViewExtension\Components\Builder;

use Livewire\ViewExtension\Utilities\Queryable;
use Livewire\ViewExtension\View;

/**
 * This class represents a component builder.
 */

abstract class ComponentBuilder
{
    public string $id;

    public View $view;

    public bool $isStorable = false;

    public bool $isQueryable = false;

    public mixed $value = null;

    public array $listeners = [];

    public Queryable $queryable;

    function __construct(View $view, string $id)
    {
        $this->view = $view;
        $this->id = $id;
        $this->queryable = new Queryable($view, $id);
    }

    public function handleQueryable(): void
    {
        // Implement your query handle here.
    }

    public function handleStorable(): void
    {
        // Implement your store handle here.
    }

    /**
     * This method will be called if a value is set.
     */
    public function onValue(mixed $value): mixed
    {
        return $value;
    }

    /**
     * Build component and register here.
     */
    public abstract function build();

    /**
     * Call this function within the build method.
     */
    public function onBuild()
    {
        if ($this->isStorable) {
            $this->handleStorable();
        }

        if ($this->isQueryable && ! $this->queryable->empty()) {
            $this->handleQueryable();
        }
    }

    /**
     * Sets value for this component.
     */
    public function value(mixed $value = null): self
    {
        $this->value = $this->onValue($value);

        return $this;
    }

    /**
     * Add new listener.
     */
    public function listen(string $type, array $options = [
        'componentId' => null,
        'callback' => null,
        'override' => false,
    ]): self
    {
        $componentId = $options['componentId'] ?? null;
        $callback = $options['callback'] ?? null;
        $override = $options['override'] ?? false;

        foreach ($this->listeners as $listener) {
            if ($listener['type'] == $type) {
                if ($listener['componentId'] ?? null == $componentId) {
                    if ($listener['callback'] ?? null == $callback) {
                        if ($listener['override'] ?? false == $override) {
                            return $this;
                        }
                    }
                }
            }
        }

        array_push($this->listeners, [
            'type' => $type,
            'componentId' => $componentId,
            'callback' => $callback,
            'override' => $override,
        ]);

        return $this;
    }

    public function toArray(array $data = []): array
    {
        return array_merge([
            'id' => $this->id,
            'listeners' => $this->listeners,
            'value' => $this->value,
            'storable' => $this->isStorable,
            'queryable' => $this->isQueryable,
        ], $data);
    }

    /** Enables store ability. */
    public function storable(): self
    {
        $this->isStorable = true;

        return $this;
    }

     /** Enables query ability. */
    public function queryable(): self
    {
        $this->isQueryable = true;

        return $this;
    }
}
