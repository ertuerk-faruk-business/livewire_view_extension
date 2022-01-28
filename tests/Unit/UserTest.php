<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;

class UserTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('users', [
                'id', 'email', 'password',
            ])
        );
    }
}
