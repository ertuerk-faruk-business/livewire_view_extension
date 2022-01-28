<?php

namespace Livewire\ViewExtension\Components;

use Livewire\ViewExtension\View;
use Livewire\ViewExtension\Components\Builder\CalendarBuilder;
use Carbon\Carbon;

/**
 * This class represents a component.
 */

class Calendar extends Component
{
    public $type = 'calendar';

    public $class = Calendar::class;

    public static function create(View $view, string $id): CalendarBuilder
    {
        return new CalendarBuilder($view, $id);
    }

    public static function inherit(array $data, mixed $view): self
    {
        return self::inheritComponent($data, $view, Calendar::class);
    }

    public function get(?int $key = null): mixed
    {
        if (! $this->hasValue()) {

            return null;
        }

        if (is_null($key)) {

            return $this->value;
        }

        return $this->value[$key];
    }

    public function format(int $key = 0, string $format = 'Y-m-d'): string
    {
        return Carbon::parse($this->value[$key])->format($format);
    }

    /** Returns a formated calendar */
    public function formatAll(string $format = 'Y-m-d', bool $validate = true): self
    {
        if (! $this->hasValue()) {
            return $this;
        }

        $values = [];

        $max = count($this->value);

        for ($i = 0; $i < $max; $i++) {
            array_push($values, $this->format($i, $format));
        }

        $this->value = $values;

        $this->update($this->value, $validate);

        return $this;
    }

    /** Returns a sorted calendar */
    public function sortBy(int $flags = SORT_NATURAL, bool $validate = true): self
    {
        if (! $this->hasValue()) {
            return $this;
        }

        sort($this->value, $flags);

        $this->update($this->value, $validate);

        return $this;
    }

    public function count(): int
    {
        if (! $this->hasValue()) {

            return 0;
        }

        return count($this->value);
    }

    public function toQueryable(): string
    {
        $values = [];

        foreach ($this->value as $value) {
            array_push($values, Carbon::parse($value)->timestamp);
        }

        return implode('-', $values);
    }
}
