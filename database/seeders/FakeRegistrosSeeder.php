<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Registro;
use Carbon\Carbon;

class FakeRegistrosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
       $faker = \Faker\Factory::create('es_ES');
        $users = User::all();

        foreach ($users as $user) {
            // Generar 5-10 registros por usuario
            $numRegistros = $faker->numberBetween(10, 20);

            for ($i = 0; $i < $numRegistros; $i++) {
                $fecha = $faker->dateTimeBetween('-3 months', 'now');
                $tipo = $faker->randomElement(['entrada', 'salida']);

                Registro::create([
                    'user_id' => $user->id,
                    'fecha_hora' => $fecha,
                    'tipo' => $tipo,
                    'created_at' => $fecha,
                    'updated_at' => $fecha,
                ]);
            }
        }
    }
}
