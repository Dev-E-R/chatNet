<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>chatNet</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-stone-100 flex justify-center items-center content-center h-screen">
    <!-- Sidebar -->
    <sidebar class="w-60 h-200 bg-stone-300 flex flex-col">
        <!-- Header del usuario -->
        <header class="bg-stone-200 p-4 flex gap-2 items-center justify-between rounded-b-lg">
            <div class="flex gap-2 items-center">
                <svg class="w-10 h-10 fill-sky-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M12 20a7.966 7.966 0 0 1-5.002-1.756l.002.001v-.683c0-1.794 1.492-3.25 3.333-3.25h3.334c1.84 0 3.333 1.456 3.333 3.25v.683A7.966 7.966 0 0 1 12 20ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10c0 5.5-4.44 9.963-9.932 10h-.138C6.438 21.962 2 17.5 2 12Zm10-5c-1.84 0-3.333 1.455-3.333 3.25S10.159 13.5 12 13.5c1.84 0 3.333-1.455 3.333-3.25S13.841 7 12 7Z" clip-rule="evenodd"/>
                </svg>
                <p>{{Str::limit(Auth::user()->name, 15) }}</p>
            </div>
            <form action="{{ url('/logout') }}" method="POST">
                @csrf
                <button type="submit" class="p-2 rounded-lg hover:bg-stone-300 transition-colors" title="Cerrar sesión">
                    <svg class="w-5 h-5 fill-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M16 5a1 1 0 0 1 1-1h2a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-2a1 1 0 1 1 0-2h2V6h-2a1 1 0 0 1-1-1Z" clip-rule="evenodd"/>
                        <path fill-rule="evenodd" d="M11.293 9.293a1 1 0 0 1 1.414 0l3 3a1 1 0 0 1 0 1.414l-3 3a1 1 0 0 1-1.414-1.414L12.586 13H4a1 1 0 1 1 0-2h8.586l-1.293-1.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </form>
        </header>

        <!-- Navegación -->
        <div class="p-2 flex gap-2">
            <a href="{{ url('/chatNet') }}" class="flex-1 text-center p-2 rounded-lg bg-sky-500 text-white font-medium hover:bg-sky-600">Chats</a>
            <a href="{{ url('/global-chat') }}" class="flex-1 text-center p-2 rounded-lg bg-purple-500 text-white font-medium hover:bg-purple-600">Global</a>
        </div>

        <!-- Botones crear -->
        <div class="p-2 flex gap-2">
            <button onclick="openNewChatModal()" class="flex-1 p-2 rounded-lg bg-green-500 text-white text-sm font-medium hover:bg-green-600 flex items-center justify-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4a1 1 0 0 1 1 1v6h6a1 1 0 1 1 0 2h-6v6a1 1 0 1 1-2 0v-6H5a1 1 0 1 1 0-2h6V5a1 1 0 0 1 1-1Z"/></svg>
                DM
            </button>
            <button onclick="openNewRoomModal()" class="flex-1 p-2 rounded-lg bg-orange-500 text-white text-sm font-medium hover:bg-orange-600 flex items-center justify-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4a1 1 0 0 1 1 1v6h6a1 1 0 1 1 0 2h-6v6a1 1 0 1 1-2 0v-6H5a1 1 0 1 1 0-2h6V5a1 1 0 0 1 1-1Z"/></svg>
                Sala
            </button>
        </div>

        <!-- Lista chats -->
        <div id="chats-list" class="flex-1 overflow-y-auto">
            <h2 class="text-ms pl-4 pt-2 text-stone-600 font-bold">Tus Conversaciones</h2>
            @forelse($chats as $chat)
                <button onclick="loadChatMessages({{ $chat->id }}, '{{ addslashes($chat->chatName) }}', {{ $chat->isGroup ? 'true' : 'false' }})" class="cursor-pointer flex justify-between bg-stone-200 m-2 p-2 w-55 rounded-lg hover:bg-stone-300 transition-colors" data-chat-id="{{ $chat->id }}"> 
                    <div class="flex flex-row pl-1 gap-1">
                        @if($chat->isGroup)
                            <svg class="w-10 h-10 fill-orange-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H6Z" clip-rule="evenodd"/>
                            </svg>
                        @else
                            <svg class="w-10 h-10 fill-stone-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M12 20a7.966 7.966 0 0 1-5.002-1.756l.002.001v-.683c0-1.794 1.492-3.25 3.333-3.25h3.334c1.84 0 3.333 1.456 3.333 3.25v.683A7.966 7.966 0 0 1 12 20ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10c0 5.5-4.44 9.963-9.932 10h-.138C6.438 21.962 2 17.5 2 12Zm10-5c-1.84 0-3.333 1.455-3.333 3.25S10.159 13.5 12 13.5c1.84 0 3.333-1.455 3.333-3.25S13.841 7 12 7Z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                        <div class="flex flex-col flex-start">
                            <p class="text-start font-medium">{{ $chat->chatName }}</p>
                            @php 
                                $lm = $chat->lastMessage ? substr($chat->lastMessage['text'], 0) : 'Sin mensajes';
                            @endphp
                            <p class="text-xs text-start text-stone-600">{{ Str::limit($lm, 20) }}</p>
                        </div>
                    </div>
                </button>
            @empty
                <div class="text-center text-stone-500 text-sm p-4">
                    <p>No tienes chats</p>
                    <p class="text-xs">Crea uno!</p>
                </div>
            @endforelse
        </div>
    </sidebar>

    <!-- Sección principal -->
    <section class="flex flex-col justify-between w-120 h-200 bg-stone-200">
        <header class="bg-stone-300 p-4 flex items-center justify-between rounded-b-lg">
            <div class="flex items-center gap-2">
                <svg id="chat-icon" class="w-10 h-10 fill-pink-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M12 20a7.966 7.966 0 0 1-5.002-1.756l.002.001v-.683c0-1.794 1.492-3.25 3.333-3.25h3.334c1.84 0 3.333 1.456 3.333 3.25v.683A7.966 7.966 0 0 1 12 20ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10c0 5.5-4.44 9.963-9.932 10h-.138C6.438 21.962 2 17.5 2 12Zm10-5c-1.84 0-3.333 1.455-3.333 3.25S10.159 13.5 12 13.5c1.84 0 3.333-1.455 3.333-3.25S13.841 7 12 7Z" clip-rule="evenodd"/>
                </svg>
                <p id="chat-header-name">Selecciona un chat</p>
            </div>
        </header>

        <div id="messages-container" class="flex-1 overflow-y-auto p-4">
            <div class="flex items-center justify-center h-full">
                <div class="text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p class="text-gray-500">Selecciona un chat</p>
                </div>
            </div>
        </div>

        <div class="p-4 bg-stone-100 rounded-t-lg ml-3 mr-3">
            <div class="flex justify-between bg-stone-200 rounded-lg p-2">
                <input type="text" id="message-input" placeholder="Escribe tu mensaje.." class="focus:outline-none w-full pr-2 pl-2 text-stone-600 bg-transparent" onkeypress="handleKeyPress(event)">            
                <button onclick="sendMessage()" class="cursor-pointer">
                    <svg class="w-6 h-6 fill-stone-400 hover:fill-sky-500 transition" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.271 5.575C8.967 4.501 7 5.43 7 7.12v9.762c0 1.69 1.967 2.618 3.271 1.544l5.927-4.881a2 2 0 0 0 0-3.088l-5.927-4.88Z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    </section>

    <!-- Modal DM -->
    <div id="newChatModal" class="hidden fixed inset-0 bg-stone-700 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-2xl shadow-2xl w-96">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Nuevo Mensaje Directo</h2>
                <button onclick="closeNewChatModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="max-h-60 overflow-y-auto border border-stone-300 rounded-lg">
                @foreach($allUsers as $user)
                <button onclick="createNewChat({{ $user->id }}, '{{ addslashes($user->name) }}')" class="w-full p-3 hover:bg-stone-100 flex items-center gap-3 border-b-stone-300 last:border-b-0">
                    <svg class="w-10 h-10 fill-stone-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M12 20a7.966 7.966 0 0 1-5.002-1.756l.002.001v-.683c0-1.794 1.492-3.25 3.333-3.25h3.334c1.84 0 3.333 1.456 3.333 3.25v.683A7.966 7.966 0 0 1 12 20ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10c0 5.5-4.44 9.963-9.932 10h-.138C6.438 21.962 2 17.5 2 12Zm10-5c-1.84 0-3.333 1.455-3.333 3.25S10.159 13.5 12 13.5c1.84 0 3.333-1.455 3.333-3.25S13.841 7 12 7Z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">{{ $user->name }}</span>
                </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal Sala -->
    <div id="newRoomModal" class="hidden fixed inset-0 bg-stone-700 bg-opacity-20 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-2xl shadow-2xl w-96">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Nueva Sala Grupal</h2>
                <button onclick="closeNewRoomModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Nombre de la Sala</label>
                <input type="text" id="room-name" class="w-full p-2 border border-stone-300 rounded-lg focus:outline-none focus:border-orange-500" placeholder="Ej: Equipo Marketing">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Selecciona miembros</label>
                <div class="max-h-60 overflow-y-auto border border-stone-300 rounded-lg">
                    @foreach($allUsers as $user)
                    <label class="flex items-center p-3 hover:bg-stone-100 border-b-stone-700 last:border-b-0 cursor-pointer">
                        <input type="checkbox" name="room-users" value="{{ $user->id }}" class="mr-3">
                        <span>{{ $user->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <button onclick="createNewRoom()" class="w-full p-2 rounded-lg bg-orange-500 text-white font-medium hover:bg-orange-600">Crear Sala</button>
        </div>
    </div>

    <script>
        let currentChatId = null;
        let isCurrentChatGroup = false;
        const CURRENT_USER_ID = {{ auth()->id() }};

        function openNewChatModal() {
            document.getElementById('newChatModal').classList.remove('hidden');
        }
        function closeNewChatModal() {
            document.getElementById('newChatModal').classList.add('hidden');
        }
        function openNewRoomModal() {
            document.getElementById('newRoomModal').classList.remove('hidden');
        }
        function closeNewRoomModal() {
            document.getElementById('newRoomModal').classList.add('hidden');
            document.getElementById('room-name').value = '';
            document.querySelectorAll('input[name="room-users"]').forEach(cb => cb.checked = false);
        }

        function createNewChat(userId, userName) {
            fetch('/chat/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ user_id: userId })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    closeNewChatModal();
                    if (!data.existing) {
                        location.reload();
                    } else {
                        loadChatMessages(data.chat_id, data.chat_name, false);
                    }
                }
            })
            .catch(e => alert('Error: ' + e.message));
        }

        function createNewRoom() {
            const name = document.getElementById('room-name').value.trim();
            const users = Array.from(document.querySelectorAll('input[name="room-users"]:checked')).map(cb => parseInt(cb.value));
            
            if (!name) return alert('Ingresa un nombre');
            if (users.length === 0) return alert('Selecciona miembros');

            fetch('/room/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ name, user_ids: users })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    closeNewRoomModal();
                    location.reload();
                }
            })
            .catch(e => alert('Error: ' + e.message));
        }

        function loadChatMessages(chatId, chatName, isGroup) {
            currentChatId = chatId;
            isCurrentChatGroup = isGroup;
            document.getElementById('chat-header-name').textContent = chatName;
            
            fetch(`/chat/${chatId}/messages`)
            .then(r => r.json())
            .then(data => {
                displayMessages(data.messages || []);
            })
            .catch(e => console.error(e));
        }

        function displayMessages(messages) {
            const container = document.getElementById('messages-container');
            if (!messages.length) {
                container.innerHTML = '<div class="flex items-center justify-center h-full"><p class="text-gray-500">No hay mensajes</p></div>';
                return;
            }
            
            container.innerHTML = messages.map(msg => {
                const isMe = msg.id == CURRENT_USER_ID;
                return `
                    <div class="flex ${isMe ? 'justify-end' : 'justify-start'} mb-4">
                        <div class="flex items-start space-x-2 max-w-md ${isMe ? 'flex-row-reverse space-x-reverse' : ''}">
                            <div class="w-8 h-8 ${isMe ? 'bg-sky-500' : 'bg-purple-500'} rounded-full flex items-center justify-center">
                                <span class="text-xs font-bold text-white">${msg.user_name[0]}</span>
                            </div>
                            <div>
                                <div class="flex items-center space-x-2 mb-1 ${isMe ? 'flex-row-reverse space-x-reverse' : ''}">
                                    <span class="text-xs font-semibold">${msg.user_name}</span>
                                    <span class="text-xs text-gray-400">${msg.formatted_time}</span>
                                </div>
                                <div class="p-3 rounded-lg ${isMe ? 'bg-sky-500 text-white' : 'bg-white'} shadow">
                                    ${msg.text}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
            container.scrollTop = container.scrollHeight;
        }

        function sendMessage() {
            const input = document.getElementById('message-input');
            const msg = input.value.trim();
            if (!currentChatId || !msg) return;

            fetch(`/chat/${currentChatId}/send`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ message: msg })
            })
            .then(r => {
                if (!r.ok) return r.json().then(e => { throw new Error(e.error) });
                return r.json();
            })
            .then(data => {
                if (data.success) {
                    loadChatMessages(currentChatId, document.getElementById('chat-header-name').textContent, isCurrentChatGroup);
                    input.value = '';
                }
            })
            .catch(e => alert('Error: ' + e.message));
        }

        function handleKeyPress(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                sendMessage();
            }
        }
    </script>
</body>
</html>
