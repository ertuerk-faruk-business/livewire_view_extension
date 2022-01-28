<?php

namespace Livewire\ViewExtension\Components\Builder;

use Livewire\ViewExtension\Components\Input;

class InputBuilder extends ComponentBuilder
{
    public function build(): Input
    {
        parent::onBuild();

        $input = Input::inherit($this->toArray(), $this->view);

        return $this->view->register($input);
    }

    public function handleStorable(): void
    {
        $this->value = $this->view->getData($this->id, $this->value);
    }

    public function handleQueryable(): void
    {
        $this->value = $this->queryable->getString();
    }
}
