<?php

namespace Livewire\ViewExtension\Components\Builder;

use Livewire\ViewExtension\Components\Toggle;

class ToggleBuilder extends ComponentBuilder
{
    public function build(): Toggle
    {
        parent::onBuild();

        if (is_null($this->value)) {
            $this->value = false;
        }

        $toggle = Toggle::inherit($this->toArray(), $this->view);

        return $this->view->register($toggle);
    }

    public function handleStorable(): void
    {
        $this->value = $this->view->getData($this->id, $this->value);
    }

    public function handleQueryable(): void
    {
        $this->value = $this->queryable->getBool();
    }
}
