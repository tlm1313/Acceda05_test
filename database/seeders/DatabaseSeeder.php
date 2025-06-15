<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\FakeUserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleUserSeeder::class,
            FotoUserSeeder::class,
            AdminUserSeeder::class,
            FakeUserSeeder::class, // Seeder para crear usuarios ficticios. Comenta esta línea si no quieres crear usuarios ficticios
            FakeRegistrosSeeder::class, // Seeder para crear registros ficticios comenta esta línea si no quieres crear registros ficticios

            // Otros seeders que quieras ejecutar
        ]);
        // User::factory(10)->create();

       /*  User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]); */
    }
}
