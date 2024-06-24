<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MangerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employees')->insert([
            'name'=>'manger',
            'email'=>'manger@gmail.com',
            'role'=> 4,
            'password'=>Hash::make('123456'),
            'branches_id'=>1,
        ]);
    }
}
