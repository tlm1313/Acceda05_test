<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Foto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;


class FakeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         $faker=faker::create();

        foreach (range(1, 50) as $index) {
            User::create([
                'name' => $faker->name,
                'apellidos' =>$faker-> lastName,
                'Dni' => $faker->unique()->regexify('[0-9]{8}[A-Z]'), // Genera un DNI ficticio
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password123'),
                'role_id' => $faker->numberBetween(1, 2), // Asignar rol aleatorio entre 1 y 2
                'foto_id' => 1, // Asignar foto por defecto
            ]);
        }
    }
}
