<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Administrateur',
            'email' => 'admin@crechebarby.fr',
            'password' => Hash::make('efxev3EBZHXJmcb'),
            'type' => 'admin',
            'active' => 1,
            'email_verified_at' => time(),
            'rgpd_accepted' => 1,
        ]);

        $user->roles()->attach($user->id, ['role_id' => 12]);
    }
}
