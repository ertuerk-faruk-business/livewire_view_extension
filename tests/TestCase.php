<?php

namespace Tests;

use App\Models\User;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Laravel\Cms\Models\Product;
use Laravel\Cms\Models\ProductBlueprint;
use Laravel\Cms\Services\TokenHandler;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public TestResponse $testResponse;

    /**
     * Will login a user which will be created automatically
     * in case no User instance will be passed.
     */
    public function login(?array $data = []): User
    {
        $user = User::factory($data)->create();

        auth()->login($user);

        (new TokenHandler())->createTokenForCurrentDevice();

        return $user;
    }

    public function fakeStorageDisk(string $disk = 'public'): FilesystemAdapter
    {
        return Storage::fake($disk);
    }

    public static function productBlueprint(): ProductBlueprint
    {
        return ProductBlueprint::create([
            'user_id' => auth()->user()->id,
            'cms_id' => 'test',
            'name' => [
                'en' => 'Test',
            ],
        ]);
    }

    public static function product(array $data = []): Product
    {
        return Product::create([
            'data' => $data,
            'blueprint_id' => self::productBlueprint()->id,
        ]);
    }
}
