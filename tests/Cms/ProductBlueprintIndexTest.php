<?php

namespace Tests\Cms;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;

class ProductBlueprintIndexTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    /** @test */
    public function a_user_has_a_product_blueprint_index_view()
    {
        $this->login();

        parent::productBlueprint();

        $this->get(route('cms.products.blueprints.index'))->assertOk();
    }
}
