<?php

namespace Tests\Unit;

use Livewire\ViewExtension\Components\Input;
use Tests\TestCase;

class InputTest extends TestCase
{
    /** @test */
    public function a_input_can_be_created()
    {
        Input::create($this->view, 'test')->build();

        $this->assertEquals($this->view->getInput('test')->id, 'test');
    }

    /** @test */
    public function a_input_can_be_created_with_a_value()
    {
        Input::create($this->view, 'test')->value('test')->build();
    
        $this->assertEquals($this->view->getInput('test')->value, 'test');
    }
}
