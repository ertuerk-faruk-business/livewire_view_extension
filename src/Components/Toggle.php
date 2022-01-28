<?php

namespace Livewire\ViewExtension\Components;

use Livewire\ViewExtension\View;
use Livewire\ViewExtension\Components\Builder\ToggleBuilder;

/**
 * This class represents a component.
 */
class Toggle extends Component
{
    public $type = 'toggle';

    public $class = Toggle::class;

    public static function create(View $view, string $id): ToggleBuilder
    {
        return new ToggleBuilder($view, $id);
    }

    public static function inherit(array $data, mixed $view): self
    {
        return self::inheritComponent($data, $view, Toggle::class);
    }

    public function isOn(): bool
    {
        return $this->value;
    }

    public function isOff(): bool
    {
        return ! $this->isOn();
    }

    public function bool(): bool|null
    {
        if (is_null($this->value)) {
            return null;
        }

        return boolval($this->value);
    }

    public function on(): self
    {
        $this->update(true);

        return $this;
    }

    public function off(): self
    {
        $this->update(false);

        return $this;
    }

    public function toggle(): self
    {
        $this->update($this->isOn() ? false : true);

        return $this;
    }

    public function toQueryable(): string
    {
        return $this->isOn() ? '1' : '0';
    }
}