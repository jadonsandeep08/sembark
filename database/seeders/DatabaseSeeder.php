<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'superadmin@sembark.com';
        $password = Hash::make('Admin@123');
        $now = now()->toDateTimeString();

        $existingUser = DB::selectOne(
            'SELECT id FROM users WHERE email = ? LIMIT 1',
            [$email]
        );

        if ($existingUser) {
            DB::update(
                'UPDATE users
                 SET company_id = NULL,
                     name = ?,
                     password = ?,
                     role = ?,
                     updated_at = ?
                 WHERE email = ?',
                [
                    'Super Admin',
                    $password,
                    'super_admin',
                    $now,
                    $email,
                ]
            );

            return;
        }

        DB::insert(
            'INSERT INTO users
                (company_id, name, email, password, role, created_at, updated_at)
             VALUES
                (NULL, ?, ?, ?, ?, ?, ?)',
            [
                'Super Admin',
                $email,
                $password,
                'super_admin',
                $now,
                $now,
            ]
        );
    }
}