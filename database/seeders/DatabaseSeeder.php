<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'displayname' => 'Test User',
            'username' => 'test',
            'email' => fake()->unique()->safeEmail(),
            'password' => 'test',
        ])->each(function ($user) {
            $user->groups()->create(['name' => 'General', 'icon' => '💾']);
        });

        \App\Models\User::factory(10)->create()->each(function ($user) {
            $group = $user->groups()->create(['name' => 'General', 'icon' => '💾']);

            for ($i = 0; $i < 20; $i++) {
                $group->posts()->create(['content' => fake()->text(400)]);
                sleep(1);
            }
        });
    }
}
