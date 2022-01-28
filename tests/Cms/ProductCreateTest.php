<?php

namespace Tests\Cms;

use Illuminate\Foundation\Testing\Concerns\InteractsWithSession;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Cms\Http\Livewire\Products\Create;
use Livewire\Livewire;

class ProductCreateTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;
    use InteractsWithSession;

    /** @test */
    public function a_product_has_a_create_view()
    {
        $this->login();

        $product = parent::product();

        $this->get(route('cms.products.index', [
            'blueprint' => $product->blueprint->cms_id,
        ]))
            ->assertOk()
            ->assertSeeLivewire('cms.products.index');
    }

    /** @test */
    public function a_product_can_be_created()
    {
        $this->login();

        $productBlueprint = parent::productBlueprint();

        $this->get(route('cms.products.index', [
            'blueprint' => $productBlueprint->cms_id,
        ]));

        $this->assertDatabaseCount('cms_products', 0);

        Livewire::test(Create::class)
            ->call('showView', null, [])
            ->call('create');

        $this->assertDatabaseCount('cms_products', 1);
    }
}
