<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Password TIDAK perlu di-Hash::make(), karena model User
        // sudah punya cast 'password' => 'hashed' (otomatis di-hash).
        // updateOrCreate: kalau username sudah ada -> diperbarui, bukan dobel.
        $users = [
            [
                'nama_user' => 'Administrator',
                'username'  => 'admin',
                'password'  => 'admin123',
                'level'     => 'admin',
                'status'    => 'aktif',
            ],
            [
                'nama_user' => 'Kasir',
                'username'  => 'kasir',
                'password'  => 'kasir123',
                'level'     => 'kasir',
                'status'    => 'aktif',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['username' => $user['username']],
                $user
            );
        }
    }
}