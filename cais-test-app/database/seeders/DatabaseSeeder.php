<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'uname' => 'adminuser',
            'upass' => 'adminpassword',
            'email' => 'admin@example.com',
            'profile_id' => 1020,
            'status' => 'active',
            'user_type' => 'admin',
        ]);

        //User::factory(10)->create();

        //Profile::factory(10)->create();
        
    }
}
