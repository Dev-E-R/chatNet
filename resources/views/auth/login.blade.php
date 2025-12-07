<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bienvenido</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-stone-100 flex justify-center items-center content-center h-screen">
    <div class="p-10 bg-white rounded-2xl shadow-2xl flex flex-col items-center justify-center">
        <h1 class="text-2xl font-semibold pb-10">Iniciar sesion</h1>
        
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
                {{ $errors->first('email') }}
            </div>
        @endif
        
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ url('/login') }}" method="POST" class="flex gap-4 flex-col">
            @csrf
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
            <button type="submit" class="cursor-pointer p-2 rounded-2xl bg-stone-400 hover:bg-sky-300 hover:text-stone-600 hover:shadow transition duration-300 ease-in-out text-white font-medium">Ingresar</button>
            <p class="text-center text-xs/1">No tienes cuenta?. <a href="{{ url('/register') }}" class="text-blue-500 font-medium">Registrate aqui</a></p>
        </form>
    </div>
</body>
</html>
