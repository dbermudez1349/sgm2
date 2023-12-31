<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            'name' => 'Diego',
            'email' => 'tecnologia.informacion@sanvicente.gob.ec',
            'password' => bcrypt('123456'),
            'idpersona' => 1
        ]);
        DB::table('users')->insert([
            'name' => 'Silvia Marlene',
            'email' => 'smzambrano@sanvicente.gob.ec',
            'password' => bcrypt('Silvia2022'),
            'idpersona' => 2
        ]);
    }
}
