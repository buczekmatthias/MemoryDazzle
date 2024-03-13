<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \DB::disableQueryLog();

        $testUser = User::factory()->create([
            'displayname' => 'Test User',
            'username' => 'test',
            'email' => fake()->unique()->safeEmail(),
            'password' => 'test',
        ]);

        $testUser->groups()->create(['name' => 'General', 'icon' => '💾']);


        User::factory(10)->create()->each(function ($user) {
            $group = $user->groups()->create(['name' => 'General', 'icon' => '💾']);

            for ($i = 0; $i < 50; $i++) {
                $group->posts()->create(['content' => fake()->text(400)]);
            }
        });
    }
}
