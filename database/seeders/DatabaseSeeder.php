<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        $admin = User::create([
            'name'=>'Romain',
            'email' => 'romain@exemple.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        $user = User::create([
            'name'=>'Maxime',
            'email' => 'maxime@exemple.com',
            'password' => Hash::make('password'),
        ]);
        $user->assignRole('user');
    }
}
