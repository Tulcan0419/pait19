<?php

namespace Database\Seeders;

use App\Models\Coordinator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Importar la fachada Hash

class CoordinatorSeeder extends Seeder
{
    /**
     * Ejecuta el seeder.
     */
    public function run(): void
    {
        // Opcional: Elimina datos existentes para evitar duplicados en cada ejecución del seeder
        // Coordinator::truncate(); // Descomenta si quieres limpiar la tabla antes de sembrar

        // Crea el coordinador de prueba si no existe
        if (!Coordinator::where('email', 'coordinador@example.com')->exists()) {
            Coordinator::create([
                'name' => 'Coordinador Principal',
                'email' => 'coordinador@example.com',
                'password' => Hash::make('password123'), // La contraseña se cifra automáticamente
            ]);
            $this->command->info('Coordinador de prueba creado: coordinador@example.com / password123');
        } else {
            $this->command->info('El coordinador de prueba ya existe.');
        }


        // Opcional: Si tienes un Factory para Coordinator, puedes crear más coordinadores de prueba
        // Coordinator::factory(5)->create();
    }
}
