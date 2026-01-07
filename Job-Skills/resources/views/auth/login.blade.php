<x-layouts.app>
    <div class="min-h-screen flex items-center justify-center py-12 px-4" style="background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8">
                <a href="/" class="inline-flex items-center gap-3">
                    <span class="w-14 h-14 rounded-2xl flex items-center justify-center text-white text-xl font-bold shadow-lg" style="background: linear-gradient(135deg, #184085 0%, #1e4d9e 100%);">JS</span>
                </a>
                <h1 class="text-2xl font-bold text-gray-800 mt-4">Bienvenue</h1>
                <p class="text-gray-500 mt-1">Connectez-vous à votre compte</p>
            </div>
            
            <!-- Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <!-- Form -->
                <form action="{{ route('login') }}" method="POST" class="p-8">
                    @csrf
                    
                    <div class="mb-5">
                        <label for="email" class="form-label">Adresse email</label>
                        <input type="email" id="email" name="email" required
                            class="form-input"
                            placeholder="vous@example.com"
                            value="{{ old('email') }}">
                        @error('email')
                            <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" id="password" name="password" required
                            class="form-input"
                            placeholder="••••••••">
                    </div>

                    <button type="submit" class="btn-primary w-full text-base py-3.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Se connecter
                    </button>
                </form>
                
                <!-- Divider -->
                <div class="px-8 pb-8">
                    <div class="relative mb-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500">ou</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('register') }}" class="btn-secondary w-full">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Créer un compte
                    </a>
                </div>
            </div>
            
            <!-- Back link -->
            <p class="text-center text-sm text-gray-500 mt-6">
                <a href="{{ route('emplois.index') }}" class="link-primary inline-flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour aux offres
                </a>
            </p>
        </div>
    </div>
</x-layouts.app>
