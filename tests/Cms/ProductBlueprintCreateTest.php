<?php

namespace Tests\Cms;

use Illuminate\Foundation\Testing\Concerns\InteractsWithSession;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Cms\Http\Livewire\Products\Blueprints\Create;
use Livewire\Livewire;

class ProductBlueprintCreateTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;
    use InteractsWithSession;

    /** @test */
    public function a_product_blueprint_has_a_create_view()
    {
        $this->login();

        $this->get(route('cms.products.blueprints.index'))
            ->assertOk()
            ->assertSeeLivewire('cms.products.blueprints.create');
    }

    /** @test */
    public function a_product_blueprint_can_be_created()
    {
        $this->login();

        Livewire::test(Create::class)
            ->call('showView')
            ->set('data.components.cms_id.value', 'test')
            ->set('data.components.name.value', 'Test')
            ->call('create');

        $this->assertDatabaseHas('cms_product_blueprints', ['cms_id' => 'test']);
    }

    /** @test */
    public function a_product_blueprint_must_have_a_cms_id()
    {
        $this->login();

        Livewire::test(Create::class)
            ->call('showView')
            ->set('data.components.name.value', 'Test')
            ->call('create');

        $this->assertDatabaseCount('cms_product_blueprints', 0);
    }

    /** @test */
    public function a_product_blueprint_must_have_a_name()
    {
        $this->login();

        Livewire::test(Create::class)
            ->call('showView')
            ->set('data.components.cms_id.value', 'test')
            ->call('create');

        $this->assertDatabaseCount('cms_product_blueprints', 0);
    }
}
