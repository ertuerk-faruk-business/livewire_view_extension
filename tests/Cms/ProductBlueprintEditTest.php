<?php

namespace Tests\Cms;

use Illuminate\Foundation\Testing\Concerns\InteractsWithSession;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Cms\Http\Livewire\Products\Blueprints\Edit;
use Livewire\Livewire;

class ProductBlueprintEditTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;
    use InteractsWithSession;

    /** @test */
    public function a_product_blueprint_has_a_edit_view()
    {
        $this->login();

        $this->get(route('cms.products.blueprints.index'))
            ->assertOk()
            ->assertSeeLivewire('cms.products.blueprints.edit');
    }

    /** @test */
    public function a_product_blueprint_can_be_edited()
    {
        $this->login();

        $blueprint = parent::productBlueprint();

        Livewire::test(Edit::class)->call('showView', null, [
            'product_blueprint_id' => $blueprint->id,
        ])
            ->set('data.components.cms_id.value', 'test2')
            ->set('data.components.name.value', 'Test 2')
            ->call('save');

        $this->assertDatabaseHas('cms_product_blueprints', [
            'cms_id' => 'test2',
        ]);
    }

    /** @test */
    public function a_product_blueprint_must_have_a_cms_id()
    {
        $this->login();

        $blueprint = parent::productBlueprint();

        Livewire::test(Edit::class)->call('showView', null, [
            'product_blueprint_id' => $blueprint->id,
        ])
            ->set('data.components.cms_id.value', '')
            ->call('save');

        $this->assertDatabaseHas('cms_product_blueprints', [
            'cms_id' => 'test',
        ]);
    }
}
