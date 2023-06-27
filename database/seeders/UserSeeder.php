<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedAdminUser();
        $this->seedUser();
    }

    public function seedAdminUser()
    {
        User::create([
            'name'       => 'Admin Teste',
            'username'       => 'administrator',
            'email'      => 'admin.test@teste.com',
            'password'   => env('LOCAL_TEST_USER_PASSWORD'),
            'type'       => 'admin'
        ]);
    }

    public function seedUser()
    {
        User::create([
            'name'       => 'User Teste',
            'username'       => 'user_teste',
            'email'      => 'user.test@teste.com',
            'password'   => env('LOCAL_TEST_USER_PASSWORD'),
            'type'       => 'user'
        ]);
    }

}
