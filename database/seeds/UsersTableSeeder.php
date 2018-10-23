<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
	public function run()
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@limehomepricing.com',
            'password' => bcrypt('123456'),
            'user_type'=>'super_admin'
        ]);
    }
}
