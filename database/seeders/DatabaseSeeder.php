<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Artisan::call('passport:client', ['--personal' => true]);

        $this->call(class: UserSeeder::class);
        $this->call(class: AccountSeeder::class);
        $this->call(class: CategorySeeder::class);
    }
}
