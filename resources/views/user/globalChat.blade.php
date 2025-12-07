<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat Global - chatNet</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-stone-100 flex justify-center items-center content-center h-screen">
    <!-- Sidebar -->
    <sidebar class="w-60 h-200 bg-stone-300 flex flex-col">
        <!-- Header del usuario -->
        <header class="bg-stone-200 p-4 flex gap-2 items-center justify-between rounded-b-lg">
            <div class="flex gap-2 items-center">
                <svg class="w-10 h-10 fill-sky-500 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M12 20a7.966 7.966 0 0 1-5.002-1.756l.002.001v-.683c0-1.794 1.492-3.25 3.333-3.25h3.334c1.84 0 3.333 1.456 3.333 3.25v.683A7.966 7.966 0 0 1 12 20ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10c0 5.5-4.44 9.963-9.932 10h-.138C6.438 21.962 2 17.5 2 12Zm10-5c-1.84 0-3.333 1.455-3.333 3.25S10.159 13.5 12 13.5c1.84 0 3.333-1.455 3.333-3.25S13.841 7 12 7Z" clip-rule="evenodd"/>
                </svg>
                <p>{{Str::limit(Auth::user()->name ,15) }}</p>
            </div>
            <!-- Botón de logout -->
            <form action="{{ url('/logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="p-2 rounded-lg hover:bg-stone-300 transition-colors" title="Cerrar sesión">
                    <svg class="w-5 h-5 fill-red-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M16 5a1 1 0 0 1 1-1h2a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-2a1 1 0 1 1 0-2h2V6h-2a1 1 0 0 1-1-1Z" clip-rule="evenodd"/>
                        <path fill-rule="evenodd" d="M11.293 9.293a1 1 0 0 1 1.414 0l3 3a1 1 0 0 1 0 1.414l-3 3a1 1 0 0 1-1.414-1.414L12.586 13H4a1 1 0 1 1 0-2h8.586l-1.293-1.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </form>
        </header>

        <!-- Botones de navegación -->
        <div class="p-2 flex gap-2">
            <a href="{{ url('/chatNet') }}" class="flex-1 text-center p-2 rounded-lg bg-stone-400 text-white font-medium hover:bg-sky-500 transition-colors">
                Chats
            </a>
            <a href="{{ url('/global-chat') }}" class="flex-1 text-center p-2 rounded-lg bg-purple-500 text-white font-medium hover:bg-purple-600 transition-colors">
                Global
            </a>
        </div>

        <!-- Información del chat global -->
        <div class="p-4 text-center">
            <div class="bg-stone-200 p-4 rounded-lg">
                <svg class="w-12 h-12 fill-purple-500 mx-auto mb-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M3 6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-6.616l-2.88 2.592C8.537 20.461 7 19.776 7 18.477V17H5a2 2 0 0 1-2-2V6Zm4 2a1 1 0 0 0 0 2h5a1 1 0 1 0 0-2H7Zm8 0a1 1 0 1 0 0 2h2a1 1 0 1 0 0-2h-2Zm-8 3a1 1 0 1 0 0 2h2a1 1 0 1 0 0-2H7Zm5 0a1 1 0 1 0 0 2h5a1 1 0 1 0 0-2h-5Z" clip-rule="evenodd"/>
                </svg>
                <h3 class="font-bold text-stone-700">Chat Global</h3>
                <p class="text-xs text-stone-600 mt-1">Conversa con todos los usuarios</p>
            </div>
        </div>
    </sidebar>

    <!-- Sección principal del chat -->
    <section class="flex flex-col justify-between w-120 h-200 bg-stone-200">
        <header class="bg-purple-500 p-4 flex items-center justify-between rounded-b-lg">
            <div class="flex items-center gap-2">
                <svg class="w-10 h-10 fill-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M3 6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-6.616l-2.88 2.592C8.537 20.461 7 19.776 7 18.477V17H5a2 2 0 0 1-2-2V6Zm4 2a1 1 0 0 0 0 2h5a1 1 0 1 0 0-2H7Zm8 0a1 1 0 1 0 0 2h2a1 1 0 1 0 0-2h-2Zm-8 3a1 1 0 1 0 0 2h2a1 1 0 1 0 0-2H7Zm5 0a1 1 0 1 0 0 2h5a1 1 0 1 0 0-2h-5Z" clip-rule="evenodd"/>
                </svg>
                <p class="text-white font-bold">Chat Global</p>
            </div>
            <div class="flex items-center">
                <button onclick="refreshMessages()" class="p-2 rounded-lg hover:bg-purple-600 transition-colors" title="Actualizar mensajes">
                    <svg class="w-6 h-6 fill-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M5 8a1 1 0 0 1 1 1v3a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1Zm4 0a1 1 0 0 1 1 1v3a1 1 0 1 1-2 0V9a1 1 0 0 1 1-1Zm4 0a1 1 0 0 1 1 1v3a1 1 0 1 1-2 0V9a1 1 0 0 1 1-1Zm4 0a1 1 0 0 1 1 1v3a1 1 0 1 1-2 0V9a1 1 0 0 1 1-1Zm4 0a1 1 0 0 1 1 1v3a1 1 0 1 1-2 0V9a1 1 0 0 1 1-1ZM5 15a1 1 0 0 1 1 1v3a1 1 0 1 1-2 0v-3a1 1 0 0 1 1-1Zm4 0a1 1 0 0 1 1 1v3a1 1 0 1 1-2 0v-3a1 1 0 0 1 1-1Zm4 0a1 1 0 0 1 1 1v3a1 1 0 1 1-2 0v-3a1 1 0 0 1 1-1Zm4 0a1 1 0 0 1 1 1v3a1 1 0 1 1-2 0v-3a1 1 0 0 1 1-1Z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </header>

        <!-- Contenedor de mensajes -->
        <div id="messages-container" class="flex-1 overflow-y-auto p-4">
            @if($messages && count($messages) > 0)
                @foreach($messages as $message)
                    <div class="flex {{ $message->user_id == auth()->id() ? 'justify-end' : 'justify-start' }} mb-4">
                        <div class="flex items-start space-x-2 max-w-md {{ $message->user_id == auth()->id() ? 'flex-row-reverse space-x-reverse' : '' }}">
                            <div class="w-8 h-8 {{ $message->user_id == auth()->id() ? 'bg-sky-500' : 'bg-purple-500' }} rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-xs font-bold text-white">{{ substr($message->user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <div class="flex items-center space-x-2 mb-1 {{ $message->user_id == auth()->id() ? 'flex-row-reverse space-x-reverse' : '' }}">
                                    <span class="text-xs font-semibold text-gray-700">{{ $message->user->name }}</span>
                                    <span class="text-xs text-gray-400">{{ $message->created_at->format('H:i') }}</span>
                                </div>
                                <div class="p-3 rounded-lg {{ $message->user_id == auth()->id() ? 'bg-sky-500 text-white' : 'bg-white text-gray-800' }} shadow">
                                    {{ $message->message }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="flex items-center justify-center h-full">
                    <div class="text-center">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <p class="text-gray-500">No hay mensajes aún. ¡Sé el primero en escribir!</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Input de mensaje -->
        <div class="p-4 bg-stone-100 rounded-t-lg ml-3 mr-3">
            <div class="flex justify-between bg-stone-200 rounded-lg p-2">
                <input type="text" id="message-input" name="message" placeholder="Escribe tu mensaje al chat global.." class="focus:outline-none w-full pr-2 pl-2 text-stone-600 bg-transparent" onkeypress="handleKeyPress(event)">            
                <button onclick="sendMessage()" class="cursor-pointer">
                    <svg class="w-6 h-6 fill-stone-400 text-gray-800 transition duration-300 hover:fill-purple-500 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M10.271 5.575C8.967 4.501 7 5.43 7 7.12v9.762c0 1.69 1.967 2.618 3.271 1.544l5.927-4.881a2 2 0 0 0 0-3.088l-5.927-4.88Z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    </section>

    @php
        $currentUserId = auth()->id() ?? 0;
    @endphp

    <script>
        const CURRENT_USER_ID = {{ $currentUserId }};

        function refreshMessages() {
            fetch('/global-chat/messages', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.messages && data.messages.length > 0) {
                    displayMessages(data.messages);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function displayMessages(messages) {
            const messagesContainer = document.getElementById('messages-container');
            
            let messagesHtml = '';
            messages.forEach(message => {
                const isCurrentUser = message.id == CURRENT_USER_ID;
                const justifyClass = isCurrentUser ? 'justify-end' : 'justify-start';
                const flexRowClass = isCurrentUser ? 'flex-row-reverse space-x-reverse' : '';
                const bgColorClass = isCurrentUser ? 'bg-sky-500 text-white' : 'bg-white text-gray-800';
                const avatarColorClass = isCurrentUser ? 'bg-sky-500' : 'bg-purple-500';
                const userInitial = message.user_name.charAt(0).toUpperCase();
                
                messagesHtml += `
                    <div class="flex ${justifyClass} mb-4">
                        <div class="flex items-start space-x-2 max-w-md ${flexRowClass}">
                            <div class="w-8 h-8 ${avatarColorClass} rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-xs font-bold text-white">${userInitial}</span>
                            </div>
                            <div>
                                <div class="flex items-center space-x-2 mb-1 ${flexRowClass}">
                                    <span class="text-xs font-semibold text-gray-700">${message.user_name}</span>
                                    <span class="text-xs text-gray-400">${message.formatted_time}</span>
                                </div>
                                <div class="p-3 rounded-lg ${bgColorClass} shadow">
                                    ${message.text}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            messagesContainer.innerHTML = messagesHtml;
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function sendMessage() {
            const messageInput = document.getElementById('message-input');
            const message = messageInput.value.trim();

            if (!message) {
                alert('Por favor, escribe un mensaje');
                return;
            }

            fetch('/global-chat/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    message: message
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.error || 'Error al enviar el mensaje');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    addMessageToDisplay(data.message);
                    messageInput.value = '';
                    messageInput.focus();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al enviar el mensaje: ' + error.message);
            });
        }

        function addMessageToDisplay(message) {
            const messagesContainer = document.getElementById('messages-container');
            
            const placeholder = messagesContainer.querySelector('.text-center');
            if (placeholder && placeholder.parentElement.classList.contains('h-full')) {
                messagesContainer.innerHTML = '';
            }

            const isCurrentUser = message.id == CURRENT_USER_ID;
            const justifyClass = isCurrentUser ? 'justify-end' : 'justify-start';
            const flexRowClass = isCurrentUser ? 'flex-row-reverse space-x-reverse' : '';
            const bgColorClass = isCurrentUser ? 'bg-sky-500 text-white' : 'bg-white text-gray-800';
            const avatarColorClass = isCurrentUser ? 'bg-sky-500' : 'bg-purple-500';
            const userInitial = message.user_name.charAt(0).toUpperCase();
            
            const messageHtml = `
                <div class="flex ${justifyClass} mb-4">
                    <div class="flex items-start space-x-2 max-w-md ${flexRowClass}">
                        <div class="w-8 h-8 ${avatarColorClass} rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-xs font-bold text-white">${userInitial}</span>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2 mb-1 ${flexRowClass}">
                                <span class="text-xs font-semibold text-gray-700">${message.user_name}</span>
                                <span class="text-xs text-gray-400">${message.formatted_time}</span>
                            </div>
                            <div class="p-3 rounded-lg ${bgColorClass} shadow">
                                ${message.text}
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function handleKeyPress(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                sendMessage();
            }
        }

        // Auto-refresh cada 5 segundos
        setInterval(refreshMessages, 5000);
    </script>
</body>
</html>
