<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new User();
        $admin->name = 'Admin';
        $admin->email = 'admin@example.com';
        $admin->password = bcrypt('password');
        $admin->gender = 'Male';
        $admin->remember_token = str_random(60);
        $admin->save();
        
        $trang = new User();
        $trang->name = 'Trang Ha Viet';
        $trang->email = 'tranghv@example.com';
        $trang->password = bcrypt('password');
        $trang->gender = 'Male';
        $trang->remember_token = str_random(60);
        $trang->save();
    }
}
