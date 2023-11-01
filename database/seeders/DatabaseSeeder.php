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
         \App\Models\Individual::factory(20)->create();

         \App\Models\User::factory()->create([
             'name' => 'Default User',
             'email' => 'default@admin.com',
         ]);
    }
}
