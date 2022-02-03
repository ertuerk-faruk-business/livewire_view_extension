<?php

namespace Livewire\ViewExtension\Components\Builder;

use Livewire\ViewExtension\Components\Selector;

class SelectorBuilder extends ComponentBuilder
{
    public array $selectables = [];

    public function build(): Selector
    {
        parent::onBuild();

        if (is_null($this->value)) {
            $this->value = [];
        }

        if (! is_array($this->value)) {
            $this->value = [
                $this->value,
            ];
        }

        $selector = Selector::inherit($this->toArray([
            'selectables' => $this->selectables,
        ]), $this->view);

        return $this->view->register($selector);
    }

    public function selectables(mixed $selectables): self
    {
        $this->selectables = $selectables;

        return $this;
    }

    public function handleStorable(): void
    {
        $this->value = $this->view->getData($this->id, $this->value);
    }

    public function handleQueryable(): void
    {
        if ($this->queryable->isArray()) {
            $this->value = $this->queryable->getArray();

            return;
        }

        $this->value = [
            $this->queryable->getString(),
        ];
    }
}
