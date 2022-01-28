<?php

namespace Livewire\ViewExtension\Components;

interface Listener
{
    /**
     * On other component updated.
     */
    const OtherComponentUpdated = 'otherComponentUpdated';

    /**
     * On component updated.
     */
    const ComponentUpdated = 'componendUpdated';
}
