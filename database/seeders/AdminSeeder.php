<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        Admin::created([
            'name'=>'Admin',
            'usename'=>'admin',
            'phone_number'=>'09332879075',
            'password' => Hash::make('danial1384'),
            'picture' => '/'
        ]);
    }
}
