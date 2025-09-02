<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Verifica si el administrador ya existe
        if (!Admin::where('email', 'admin@example.com')->exists()) {
            Admin::create([
                'name' => 'Administrador',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'), // Asegúrate de usar un hash para la contraseña
            ]);
        } else {
            $this->command->info('El administrador ya existe.');
        }
    }
}
