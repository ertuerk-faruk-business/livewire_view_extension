<?php

namespace Livewire\ViewExtension;

abstract class Action
{
    public $context;

    public function __construct(mixed $context = null)
    {
        $this->context = $context;
    }

    abstract public function handle(View $view): mixed;
}
