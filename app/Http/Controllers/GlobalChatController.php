<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GlobalChat;
use Illuminate\Support\Facades\Auth;

class GlobalChatController extends Controller
{
    public function index()
    {
        // Obtener los últimos 50 mensajes del chat global
        $messages = GlobalChat::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->reverse()
            ->values();

        return view('user.globalChat', compact('messages'));
    }

    public function getMessages()
    {
        $messages = GlobalChat::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->reverse()
            ->map(function($message) {
                return [
                    'id' => $message->user_id,
                    'user_name' => $message->user->name,
                    'text' => $message->message,
                    'formatted_time' => $message->created_at->format('H:i'),
                ];
            });

        return response()->json([
            'messages' => $messages,
        ]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = GlobalChat::create([
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->user_id,
                'user_name' => Auth::user()->name,
                'text' => $message->message,
                'formatted_time' => $message->created_at->format('H:i'),
            ]
        ]);
    }
}
