<?php

namespace Tests\Cms;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;

class ProductIndexTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    /** @test */
    public function a_product_has_a_index_view()
    {
        $this->login();

        $product = parent::product();

        $this->get(route('cms.products.index', [
            'blueprint' => $product->blueprint->cms_id,
        ]))->assertOk();
    }

    /** @test */
    public function a_product_index_view_has_livewire()
    {
        $this->login();

        $product = parent::product();

        $this->get(route('cms.products.index', [
            'blueprint' => $product->blueprint->cms_id,
        ]))->assertSeeLivewire('cms.products.index');
    }
}
