<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    /**
     * Mostrar la lista de salas disponibles para el usuario
     */
    public function index()
    {
        $userId = Auth::id();
        
        // Obtener salas donde el usuario está permitido
        $rooms = Room::all()->filter(function ($room) use ($userId) {
            $allowedUsers = explode(',', $room->allowedusers);
            return in_array($userId, $allowedUsers);
        });

        return view('chat.rooms', compact('rooms'));
    }

    /**
     * Mostrar formulario para crear una nueva sala
     */
    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('chat.create-room', compact('users'));
    }

    /**
     * Crear una nueva sala
     */
    public function store(Request $request)
    {
        $request->validate([
            'users' => 'required|array|min:1',
            'users.*' => 'exists:users,id',
        ]);

        // Incluir al usuario actual en la lista de usuarios permitidos
        $allowedUsers = array_unique(array_merge($request->users, [Auth::id()]));
        sort($allowedUsers);

        $room = Room::create([
            'allText' => '', // Inicialmente vacío
            'allowedusers' => implode(',', $allowedUsers),
        ]);

        return redirect()->route('rooms.show', $room->id)
            ->with('success', 'Sala creada correctamente.');
    }

    /**
     * Mostrar el chat de una sala específica
     */
    public function show($id)
    {
        $room = Room::findOrFail($id);
        $userId = Auth::id();

        // Verificar que el usuario tiene permiso para acceder a esta sala
        $allowedUsers = explode(',', $room->allowedusers);
        if (!in_array($userId, $allowedUsers)) {
            abort(403, 'No tienes permiso para acceder a esta sala.');
        }

        // Parsear los mensajes
        $messages = $this->parseMessages($room->allText);
        
        // Obtener usuarios de la sala
        $roomUsers = User::whereIn('id', $allowedUsers)->get();

        return view('chat.room-chat', compact('room', 'messages', 'roomUsers'));
    }

    /**
     * Obtener mensajes de una sala (API para actualización en tiempo real)
     */
    public function getMessages($id, Request $request)
    {
        $room = Room::findOrFail($id);
        $userId = Auth::id();

        // Verificar permisos
        $allowedUsers = explode(',', $room->allowedusers);
        if (!in_array($userId, $allowedUsers)) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $messages = $this->parseMessages($room->allText);

        return response()->json([
            'messages' => $messages,
        ]);
    }

    /**
     * Enviar un mensaje a una sala
     */
    public function sendMessage($id, Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $room = Room::findOrFail($id);
        $userId = Auth::id();

        // Verificar permisos
        $allowedUsers = explode(',', $room->allowedusers);
        if (!in_array($userId, $allowedUsers)) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        // Escapar comillas en el mensaje
        $message = str_replace('"', '\\"', $request->message);
        
        // Crear el nuevo mensaje en formato: {id:user_id,text:"message",time:timestamp}
        $newMessage = sprintf(
            '{id:%d,text:"%s",time:%d}',
            $userId,
            $message,
            time()
        );

        // Agregar el mensaje al allText
        $currentText = $room->allText;
        if (!empty($currentText)) {
            $room->allText = $currentText . '|' . $newMessage;
        } else {
            $room->allText = $newMessage;
        }

        $room->save();

        // Obtener información del usuario
        $user = User::find($userId);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $userId,
                'user_name' => $user->name,
                'user_icon' => $user->icon,
                'text' => $request->message,
                'time' => time(),
            ],
        ]);
    }

    /**
     * Parsear el campo allText a un array de mensajes
     */
    private function parseMessages($allText)
    {
        if (empty($allText)) {
            return [];
        }

        $messages = [];
        $messageStrings = explode('|', $allText);

        foreach ($messageStrings as $msgStr) {
            // Parsear: {id:1,text:"hello",time:123456}
            if (preg_match('/\{id:(\d+),text:"(.+?)",time:(\d+)\}/', $msgStr, $matches)) {
                $userId = $matches[1];
                $text = str_replace('\\"', '"', $matches[2]); // Revertir escape de comillas
                $time = $matches[3];

                $user = User::find($userId);

                $messages[] = [
                    'id' => $userId,
                    'user_name' => $user ? $user->name : 'Usuario desconocido',
                    'user_icon' => $user ? $user->icon : 'public/icons/default.png',
                    'text' => $text,
                    'time' => $time,
                    'formatted_time' => date('Y-m-d H:i:s', $time),
                ];
            }
        }

        return $messages;
    }

    /**
     * Eliminar una sala (solo si el usuario es uno de los miembros)
     */
    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $userId = Auth::id();

        // Verificar permisos
        $allowedUsers = explode(',', $room->allowedusers);
        if (!in_array($userId, $allowedUsers)) {
            return redirect()->back()->with('error', 'No tienes permiso para eliminar esta sala.');
        }

        $room->delete();

        return redirect()->route('rooms.index')
            ->with('success', 'Sala eliminada correctamente.');
    }
}