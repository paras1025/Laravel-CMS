<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', 'anubhav.baweja@daffodilsw.com')->first();

        if(!$user)
        {
        	User::create([
        		'name' => 'Anubhav Baweja',
        		'email' => 'anubhav.baweja@daffodilsw.com',
        		'role' => 'admin',
        		'password' => Hash::make('password')
        		]);
        }
    }
}
