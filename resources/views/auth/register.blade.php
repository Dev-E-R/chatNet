<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Un paso mas!</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-stone-100 flex justify-center items-center content-center h-screen">
    <div class="p-10 bg-white rounded-2xl shadow-2xl flex flex-col items-center justify-center">
        <h1 class="text-2xl font-semibold pb-10">Registrate</h1>
        
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm w-full">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('/register') }}" method="POST" class="flex gap-4 flex-col">
            @csrf
            <div class="flex flex-col gap-2">
                <label for="name" class="text-sm">Username</label>
                <input required 
                    class="focus:outline-none focus:border-sky-400 transition duration-300 ease-in-out p-1 rounded-2xl border-2 border-stone-300" 
                    type="text" 
                    name="name" 
                    id="name"
                    value="{{ old('name') }}">
            </div>
            <div class="flex flex-col gap-2">
                <label for="email" class="text-sm">Email</label>
                <input required 
                    class="focus:outline-none focus:border-sky-400 transition duration-300 ease-in-out p-1 rounded-2xl border-2 border-stone-300" 
                    type="email" 
                    name="email" 
                    id="email"
                    value="{{ old('email') }}">
            </div>
            <div class="flex flex-col gap-2">
                <label for="password" class="text-sm">Password</label>
                <input required 
                    class="focus:outline-none focus:border-sky-400 transition duration-300 ease-in-out p-1 rounded-2xl border-2 border-stone-300" 
                    type="password" 
                    name="password" 
                    id="password">
            </div>
            <div class="flex flex-col gap-2">
                <label for="password_confirmation" class="text-sm">Confirm Password</label>
                <input required 
                    class="focus:outline-none focus:border-sky-400 transition duration-300 ease-in-out p-1 rounded-2xl border-2 border-stone-300" 
                    type="password" 
                    name="password_confirmation" 
                    id="password_confirmation">
            </div>
            <button type="submit" class="cursor-pointer p-2 rounded-2xl bg-stone-400 hover:bg-sky-300 hover:text-stone-600 hover:shadow transition duration-300 ease-in-out text-white font-medium">Crear cuenta</button>
            <p class="text-center text-xs/1">Ya tienes cuenta?. <a href="{{ url('/login') }}" class="text-blue-500 font-medium">Inicia Sesion aqui</a></p>
        </form>
    </div>
</body>
</html>
