<?php

namespace Livewire\ViewExtension;

abstract class Action
{
    abstract public function handle(View $view, array $data = []): mixed;
}
