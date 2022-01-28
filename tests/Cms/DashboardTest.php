<?php

namespace Tests\Cms;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;

class DashboardTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    /** @test */
    public function a_user_has_a_dashboard_view()
    {
        $this->login();

        $this->get(route('cms.dashboard.index'))
            ->assertOk();
    }

    /** @test */
    public function a_guest_has_not_a_dashboard_view()
    {
        $this->get(route('cms.dashboard.index'))
            ->assertRedirect();
    }
}
