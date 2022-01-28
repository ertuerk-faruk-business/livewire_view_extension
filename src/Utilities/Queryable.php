<?php

namespace Livewire\ViewExtension\Utilities;

use Livewire\ViewExtension\View;

class Queryable
{
    public $value;

    public string $id;

    public function __construct(View $view, string $id)
    {
        $this->id = $id;

        $this->value = $view->httpParameters[$this->id] ?? null;
    }

    public function empty(): bool
    {
        return empty($this->value);
    }

    public function getString(): string
    {
        return $this->value;
    }

    public function getBool(): bool
    {
        return boolval($this->value);
    }

    public function getArray(string $seperator = '-'): array
    {
        return explode($seperator, $this->value);
    }

    public function isArray(string $seperator = '-'): bool
    {
        return str_contains($this->value, $seperator);
    }
}
