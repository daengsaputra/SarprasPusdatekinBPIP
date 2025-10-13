<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create or update the admin user (username: daeng, password: 1234)
        $user = User::firstOrCreate(
            ['name' => 'daeng'],
            [
                'email' => 'daeng@example.com',
                'password' => Hash::make('1234'),
                'role' => 'admin',
            ]
        );

        // Ensure password is up to date if user existed
        if (!Hash::check('1234', $user->password)) {
            $user->password = Hash::make('1234');
            $user->role = 'admin';
            $user->save();
        }

        // Petugas: naufal / 12345
        $u1 = User::firstOrCreate(
            ['name' => 'naufal'],
            [
                'email' => 'naufal@example.com',
                'password' => Hash::make('12345'),
                'role' => 'petugas',
            ]
        );
        if (!Hash::check('12345', $u1->password)) {
            $u1->password = Hash::make('12345');
            $u1->role = 'petugas';
            $u1->save();
        }

        // Petugas: wahyu / 12345
        $u2 = User::firstOrCreate(
            ['name' => 'wahyu'],
            [
                'email' => 'wahyu@example.com',
                'password' => Hash::make('12345'),
                'role' => 'petugas',
            ]
        );
        if (!Hash::check('12345', $u2->password)) {
            $u2->password = Hash::make('12345');
            $u2->role = 'petugas';
            $u2->save();
        }
    }
}
