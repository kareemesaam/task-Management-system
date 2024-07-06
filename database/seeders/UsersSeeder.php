<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use function Laravel\Prompts\suggest;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name'=>'Admin',
            'type' => User::ADMIN,
            'email' => 'admin@convertedin.com'
        ]);

        User::factory(99)->create(['type' => User::ADMIN]);
        User::factory(1000)->create();

    }
}
