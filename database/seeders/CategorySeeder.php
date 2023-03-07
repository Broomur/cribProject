<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([ 'name' => 'commission animation', 'role_id' => '4' ]);
        DB::table('categories')->insert([ 'name' => 'commission bio', 'role_id' => '5' ]);
        DB::table('categories')->insert([ 'name' => 'commission recrutement', 'role_id' => '6' ]);
        DB::table('categories')->insert([ 'name' => 'commission logistique', 'role_id' => '7' ]);
        DB::table('categories')->insert([ 'name' => 'cuisine', 'role_id' => '8' ]);
        DB::table('categories')->insert([ 'name' => 'activités', 'role_id' => '9' ]);
        DB::table('categories')->insert([ 'name' => 'infirmerie', 'role_id' => '10' ]);
        DB::table('categories')->insert([ 'name' => 'administration', 'role_id' => '11' ]);
        DB::table('categories')->insert([ 'name' => 'dev', 'role_id' => '12' ]);
    }
}
