<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Remember to order the roles by importance (pending always first, admin always last).
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([ 'name' => 'compte désactivé' ]);
        DB::table('roles')->insert([ 'name' => 'compte inactif' ]);
        DB::table('roles')->insert([ 'name' => 'parent' ]);
        DB::table('roles')->insert([ 'name' => 'commission animation' ]);
        DB::table('roles')->insert([ 'name' => 'commission bio' ]);
        DB::table('roles')->insert([ 'name' => 'commission recrutement' ]);
        DB::table('roles')->insert([ 'name' => 'commission logistique' ]);
        DB::table('roles')->insert([ 'name' => 'cuisinier·e' ]);
        DB::table('roles')->insert([ 'name' => 'professionnel·le' ]);
        DB::table('roles')->insert([ 'name' => 'infirmier·ère' ]);
        DB::table('roles')->insert([ 'name' => 'directeur·rice' ]);
        DB::table('roles')->insert([ 'name' => 'admin' ]);
    }
}
