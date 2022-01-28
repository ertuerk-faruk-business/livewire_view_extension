<?php

namespace Livewire\ViewExtension\Components;

use Livewire\ViewExtension\View;
use Livewire\ViewExtension\Components\Builder\InputBuilder;

/**
 * This class represents a component.
 */
class Input extends Component
{
    public $type = 'input';

    public $class = Input::class;

    public static function create(View $view, string $id): InputBuilder
    {
        return new InputBuilder($view, $id);
    }

    public static function inherit(array $data, mixed $view): self
    {
        return self::inheritComponent($data, $view, Input::class);
    }

    public function float(): float|null
    {
        if (is_null($this->value)) {
            return null;
        }

        return floatval($this->value);
    }

    public function int(): int|null
    {
        if (is_null($this->value)) {
            return null;
        }

        return intval($this->value);
    }

    public function string(): string|null
    {
        if (is_null($this->value)) {
            return null;
        }

        return strval($this->value);
    }

    public function bool(): bool|null
    {
        if (is_null($this->value)) {
            return null;
        }

        return boolval($this->value);
    }

    public function toQueryable(): string
    {
        return $this->value;
    }
}
