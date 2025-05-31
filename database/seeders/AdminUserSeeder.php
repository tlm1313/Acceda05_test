<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Foto;


class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $defaultFoto= Foto:: where('id', 1)->first();
        User::create([
            'name' => 'Admin',
            'apellidos' => 'tlm',
            'Dni' => '12345678A',
            'email' => 'a@a.es',
            'password' => Hash::make('12345678'), // Contraseña encriptada
            'role_id' => 1, // Asignar rol de administrador
            'foto_id' => $defaultFoto->id, // Asignar foto por defecto
        ]);
    }
}
