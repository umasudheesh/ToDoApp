<?php

use Illuminate\Database\Seeder;
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
        DB::table('users')->insert([
            'name' => 'AbcTest',
            'email' => 'abc@test.com',
            'password' => Hash::make('abc@1234'),
            'created_at' => '2022-01-01 12:12:30',
            'updated_at' => '2022-01-01 12:12:30',
        ]);

        DB::table('users')->insert([
            'name' => 'EfgTest',
            'email' => 'efg@test.com',
            'password' => Hash::make('efg@1234'),
            'created_at' => '2022-01-01 12:12:30',
            'updated_at' => '2022-01-01 12:12:30',
        ]);

        DB::table('users')->insert([
            'name' => 'HijTest',
            'email' => 'hij@test.com',
            'password' => Hash::make('hij@1234'),
            'created_at' => '2022-01-01 12:12:30',
            'updated_at' => '2022-01-01 12:12:30',
        ]);

        DB::table('users')->insert([
            'name' => 'KlmTest',
            'email' => 'klm@test.com',
            'password' => Hash::make('klm@1234'),
            'created_at' => '2022-01-01 12:12:30',
            'updated_at' => '2022-01-01 12:12:30',
        ]);

        DB::table('users')->insert([
            'name' => 'NopTest',
            'email' => 'nop@test.com',
            'password' => Hash::make('nop@1234'),
            'created_at' => '2022-01-01 12:12:30',
            'updated_at' => '2022-01-01 12:12:30',
        ]);
    }
}
