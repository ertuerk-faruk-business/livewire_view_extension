<?php

namespace Tests;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Tests\Helpers\ViewHelper;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public ViewHelper $view;

    public function setUp(): void
    {
        parent::setUp();

        $this->view = new ViewHelper();
    }

    public function fakeStorageDisk(string $disk = 'public'): FilesystemAdapter
    {
        return Storage::fake($disk);
    }
}
