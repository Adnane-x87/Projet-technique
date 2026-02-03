@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-[calc(100vh-200px)] py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <!-- Card -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <!-- Title -->
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Connexion</h1>
            <p class="text-gray-600 text-sm mb-6">Accédez à votre compte JobSkills</p>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-800 text-sm font-semibold mb-2">Erreur de connexion:</p>
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-red-700 text-sm">• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-900 mb-2">Adresse Email</label>
                    <div class="relative">
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required autofocus
                               class="py-3 px-4 block w-full border-2 border-gray-200 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition @error('email') border-red-500 @enderror"
                               placeholder="vous@exemple.com">
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-900 mb-2">Mot de passe</label>
                    <div class="relative">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required
                               class="py-3 px-4 block w-full border-2 border-gray-200 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition @error('password') border-red-500 @enderror"
                               placeholder="Entrez votre mot de passe">
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="remember" 
                           name="remember" 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                    <label for="remember" class="ml-2 block text-sm text-gray-700 cursor-pointer">
                        Se souvenir de moi
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full py-3 px-4 inline-flex justify-center items-center gap-2 rounded-lg font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 mt-4">
                    Se connecter
                </button>
            </form>

            <!-- Divider -->
            <div class="my-6 flex items-center gap-3">
                <div class="flex-1 bg-gray-200 h-px"></div>
                <span class="text-sm text-gray-500">ou</span>
                <div class="flex-1 bg-gray-200 h-px"></div>
            </div>

            <!-- Sign Up Link -->
            <div class="text-center">
                <p class="text-gray-600 text-sm">
                    Vous n'avez pas de compte?
                    <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-700 hover:underline">
                        S'inscrire
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
