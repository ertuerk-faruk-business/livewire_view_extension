<?php

namespace Livewire\ViewExtension\Components\Builder;

use Livewire\ViewExtension\Components\Calendar;
use Carbon\Carbon;

class CalendarBuilder extends ComponentBuilder
{
    public function build(): Calendar
    {
        parent::onBuild();

        $calendar = Calendar::inherit($this->toArray(), $this->view);

        return $this->view->register($calendar);
    }

    public function handleStorable(): void
    {
        $this->value = $this->view->getData($this->id, $this->value);
    }

    public function handleQueryable(): void
    {
        $values = [];
        if ($this->queryable->isArray()) {
            $timestamps = $this->queryable->getArray();

            foreach($timestamps as $timestamp) {
                array_push($values, Carbon::createFromTimestamp($timestamp)->timestamp);
            }
        } else {
            array_push($values, Carbon::createFromTimestamp($this->queryable->getString())->timestamp);
        }
        if (! is_null($this->value)) {
            if (count($this->value) <= count($values)) {
                $this->value = $values;
            }
        } else {
            $this->value = $values;
        }
    }

    public function onValue(mixed $value): mixed
    {
        $value = empty($value) ? [
            Carbon::now(),
            ] : $value;

        if (! is_array($value)) {
            $value = [
                $value,
            ];
        }

        return $value;
    }
}
