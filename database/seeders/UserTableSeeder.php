<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleAdmin= Role::where('name', 'admin')->first();
        $roleCtv = Role::where('name', 'ctv')->first();
        $roleOther = Role::where('name', 'other')->first();

        $adminWeb = new User();
        $adminWeb->username = 'hainao9tk@gmail.com';
        $adminWeb->email = 'hainao9tk@gmail.com';
        $adminWeb->password = Hash::make(env("ADMIN_PASS", "123456789"));
        $adminWeb->money = 50000;
        $adminWeb->save();
        $adminWeb->roles()->attach($roleAdmin);  

    }
}
