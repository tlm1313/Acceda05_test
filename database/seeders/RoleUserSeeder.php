<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Role::create(['id' => 1,
                        'nombre_rol' => 'Administrador']);
        Role::create(['id' => 2,
                        'nombre_rol' => 'Usuario']);
    }
}
