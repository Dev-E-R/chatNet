<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\GlobalChat;
use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Job;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear UN Administrador
        User::factory()->create([
            'name' => 'Carlos Gael Guerrero Salazar',
            'icon' => 'public/icons/default.png',
            'email' => 'aditya08@example.org',
            'password' => bcrypt('12345678'), // Contraseña fácil para pruebas
        ]);
        User::factory()->create([
            'name' => 'Test User',
            'icon' => 'public/icons/default.png',
            'email' => 'test@example.org',
            'password' => bcrypt('12345678'), // Contraseña fácil para pruebas
        ]);


        // 2. Crear 5 Empleadores (Como ya lo tenías)
        User::factory(5)->create([
            'password' => bcrypt('test1234'),// Contraseña fácil para pruebas
            'icon' => 'public/icons/default.png',
            'name' => fn() => 'Employer_' . fake()->unique()->userName(),
        ]);
        
        // --- INICIO DE LÓGICA DE PRUEBA DE CHAT ---

        // 3. Crear 10 usuarios adicionales (para tener 16 en total, y usar los últimos 10 para los mensajes)
        User::factory(10)->create();

        // 4. Obtener los IDs de los 10 últimos usuarios creados
        // Usamos el Query Builder para obtener solo los IDs de los 10 últimos usuarios
        $user_ids = DB::table('users')->orderBy('id', 'desc')->limit(10)->pluck('id');
        
        // 5. Crear 10 mensajes en Global_chat (uno por cada uno de los 10 usuarios)
        echo "Creando 10 mensajes en Global_chat...\n";
        $user_ids->each(function ($userId) {
            GlobalChat::factory()->create([
                'user_id' => $userId,
                'message' => 'Mensaje de prueba en Chat Global del usuario ID: ' . $userId . '. ' . fake()->sentence(),
            ]);
        });

        // 6. Crear 1 sala de chat (Room) con los 10 usuarios y sus 10 mensajes
        echo "Creando 1 sala con 10 mensajes...\n";
        
        // Preparamos los datos para la sala
        $allowedUsers = $user_ids->implode(','); // Formato "1,2,3,..."
        $allText = ''; // Cadena de mensajes para el campo 'allText'
        
        // Iteramos para crear la cadena de mensajes según tu estructura:
        // {id:user_id,text:message,time:timestamp}|...
        $user_ids->each(function ($userId, $index) use (&$allText) {
            $message = 'Mensaje de prueba en Sala del usuario ID: ' . $userId . '. ' . fake()->sentence();
            $time = time() + $index; // Aseguramos tiempos ascendentes
            
            $allText .= "{id:$userId,text:\"$message\",time:$time}";
            
            // Agregamos el separador "|" si no es el último mensaje
            if ($index < 9) {
                $allText .= '|';
            }
        });

        // Creamos la sala con los datos preparados
        Room::factory()->create([
            'allText' => $allText,
            'allowedusers' => $allowedUsers,
        ]);

        // --- FIN DE LÓGICA DE PRUEBA DE CHAT ---
    }
}