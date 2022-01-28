<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Laravel\Cms\Models\CmsUser;
use Illuminate\Support\Str;

class CmsUserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        CmsUser::create([
            'email' => 'ertuerk.faruk@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('secret'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'first_name' => 'Faruk',
            'last_name' => 'Ertürk',
        ]);
    }
}
