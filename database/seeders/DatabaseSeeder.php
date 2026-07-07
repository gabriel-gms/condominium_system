<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //-------seed units---------
        DB::table('units')->insert([
            'name' => 'APT 1',
            'id_owner' => '1'
        ]);

        DB::table('units')->insert([
            'name' => 'APT 2',
            'id_owner' => '1'
        ]);

        DB::table('units')->insert([
            'name' => 'APT 3',
            'id_owner' => '0'
        ]);

        DB::table('units')->insert([
            'name' => 'APT 4',
            'id_owner' => '0'
        ]);
        //-------- end seed units ----------



        //---------- seed areas -----------
        DB::table('areas')->insert([
            'allowed' => '1',
            'title' => 'Academia',
            'cover' => 'gym.jpg',
            'days' => '1,2,3,4,5',
            'start_time' => '06:00:00',
            'end_time' => '22:00:00'
        ]);

        DB::table('areas')->insert([
            'allowed' => '1',
            'title' => 'Piscina',
            'cover' => 'pool.jpg',
            'days' => '1,2,3,4,5',
            'start_time' => '07:00:00',
            'end_time' => '23:00:00'
        ]);

        DB::table('areas')->insert([
            'allowed' => '1',
            'title' => 'Churrasqueira',
            'cover' => 'barbecue.jpg',
            'days' => '4,5,6',
            'start_time' => '09:00:00',
            'end_time' => '23:00:00'
        ]);
        //-------- end seed areas ----------



        //--------- seed waals --------------
        DB::table('walls')->insert([
            'title' => 'Aumento de mensalidade',
            'body' => 'A partir de 15/07/2026 a mensalidade do condomínio irá aumentar',
            'created_at' => '2026-07-07 12:00:00'
        ]);
        //--------- end seed walls------------
    }
}
