<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserChatController extends Controller
{
    public function index()
    {
        $currentUserId = auth()->id();
        
        // MEJORADO: Usar LIKE con comas para evitar coincidencias falsas
        // Si tu ID es 1, busca ",1," o "1," al inicio o ",1" al final
        $chats = Room::where(function($query) use ($currentUserId) {
            $query->where('allowedusers', 'like', $currentUserId . ',%')  // Empieza con tu ID
                  ->orWhere('allowedusers', 'like', '%,' . $currentUserId . ',%')  // En medio
                  ->orWhere('allowedusers', 'like', '%,' . $currentUserId)  // Al final
                  ->orWhere('allowedusers', '=', $currentUserId);  // Solo tu ID
        })
        ->orderBy('updated_at', 'desc')
        ->get();

        // Agregar info de cada chat
        foreach ($chats as $chat) {
            $chat->unreadMessages = 0;
            
            $userIds = explode(',', $chat->allowedusers);
            $userIds = array_map('trim', $userIds);
            
            // DM (2 personas) o Grupo (más de 2)
            if (count($userIds) == 2) {
                $chat->isGroup = false;
                // Buscar el otro usuario
                foreach ($userIds as $uid) {
                    if ($uid != $currentUserId) {
                        $otherUser = User::find($uid);
                        $chat->chatName = $otherUser ? $otherUser->name : 'Usuario';
                        break;
                    }
                }
            } else {
                $chat->isGroup = true;
                $chat->chatName = $chat->name ?? 'Sala ' . $chat->id;
            }
        }

        $messages = [];
        $allUsers = User::where('id', '!=', auth()->id())->get();
        
        return view('user.userChat', compact('chats', 'messages', 'allUsers'));
    }

    public function getMessages($id)
    {
        $room = Room::findOrFail($id);
        $userId = auth()->id();

        // Verificar con el mismo patrón mejorado
        $userIds = explode(',', $room->allowedusers);
        $userIds = array_map('trim', $userIds);
        
        if (!in_array((string)$userId, $userIds)) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $messages = $this->parseMessages($room->allText);
        return response()->json(['messages' => $messages]);
    }

    private function parseMessages($allText)
    {
        if (empty($allText)) return [];

        $messages = [];
        $parts = explode('|', $allText);

        foreach ($parts as $part) {
            if (preg_match('/\{id:(\d+),text:"(.+?)",time:(\d+)\}/', $part, $m)) {
                $user = User::find($m[1]);
                $messages[] = [
                    'id' => (int)$m[1],
                    'user_name' => $user ? $user->name : 'Usuario',
                    'text' => str_replace('\\"', '"', $m[2]),
                    'time' => $m[3],
                    'formatted_time' => date('H:i', $m[3]),
                ];
            }
        }
        return $messages;
    }

    public function sendMessage($id, Request $request)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        $room = Room::findOrFail($id);
        $userId = auth()->id();

        // Verificar permisos
        $userIds = explode(',', $room->allowedusers);
        $userIds = array_map('trim', $userIds);
        
        if (!in_array((string)$userId, $userIds)) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $text = str_replace('"', '\\"', $request->message);
        $time = time();
        $newMsg = "{id:{$userId},text:\"{$text}\",time:{$time}}";

        $room->allText = empty($room->allText) ? $newMsg : $room->allText . '|' . $newMsg;
        $room->save();

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $userId,
                'user_name' => auth()->user()->name,
                'text' => $request->message,
                'time' => $time,
                'formatted_time' => date('H:i', $time),
            ]
        ]);
    }

    public function createChat(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);

        $currentUserId = auth()->id();
        $otherUserId = $request->user_id;

        // Verificar si existe un DM (whereNull asegura que solo busque DMs)
        $existing = Room::whereNull('name')
            ->where(function($query) use ($currentUserId, $otherUserId) {
                $query->where('allowedusers', $currentUserId . ',' . $otherUserId)
                      ->orWhere('allowedusers', $otherUserId . ',' . $currentUserId);
            })
            ->first();

        if ($existing) {
            return response()->json([
                'success' => true,
                'chat_id' => $existing->id,
                'chat_name' => User::find($otherUserId)->name,
                'existing' => true
            ]);
        }

        // IMPORTANTE: Crear DM con name = NULL para distinguirlo de salas
        $room = Room::create([
            'name' => null,  // ← ESTO ES CLAVE
            'allowedusers' => $currentUserId . ',' . $otherUserId,
            'allText' => '',
        ]);

        return response()->json([
            'success' => true,
            'chat_id' => $room->id,
            'chat_name' => User::find($otherUserId)->name,
            'existing' => false
        ]);
    }

    public function createRoom(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
        ]);

        $userIds = $request->user_ids;
        $currentUserId = auth()->id();
        
        if (!in_array($currentUserId, $userIds)) {
            $userIds[] = $currentUserId;
        }

        $room = Room::create([
            'name' => $request->name,
            'allowedusers' => implode(',', $userIds),
            'allText' => '',
        ]);

        return response()->json([
            'success' => true,
            'room_id' => $room->id,
            'room_name' => $room->name,
        ]);
    }
}
