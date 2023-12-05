<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Validation Errors -->
    <x-auth-validation-errors class="mb-4" :errors="$errors" />

    <div class="container mx-auto">
        <!-- Logo Sei-Tu -->
        <div class="h-24 absolute top-5 left-5">
            <img class="h-24" src="{{ asset('img/logo_netsmart2.png') }}" alt="logo">
        </div>

        <!-- Root element for center items -->
        <div class="flex flex-col h-screen">
            <!-- Auth Card Container -->
            <div class="grid place-items-center mx-2 my-20 sm:my-auto">
                <!-- Auth Card -->
                <div class="w-10/12 p-12 sm:w-8/12 md:w-6/12 lg:w-5/12 2xl:w-4/12 
                    px-6 py-10 sm:px-10 sm:py-10 
                    bg-white rounded-lg shadow-md lg:shadow-lg opacity-90">
    
                    <!-- Card Title -->
                    <h2 class="text-center font-bold text-3xl lg:text-4xl text-gray-800 mb-6">
                        Login
                    </h2>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Input -->
                        <label for="login" class="block font-semibold text-gray-600">Usuario / E-mail</label>
                        <x-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('login')" required autofocus />

                        <!-- Password Input -->
                        <label for="password" class="block mt-6 font-semibold text-gray-600">Contraseña</label>
                        <x-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

                        <!-- Remember Me -->
                        {{-- <div class="block mt-4">
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                                <span class="ml-2 text-sm text-gray-600">{{ __('Recordar me') }}</span>
                            </label>
                        </div> --}}
                        <!-- Remember Me -->
                        {{-- <div class="flex items-center justify-end">
                            @if (Route::has('password.request'))
                                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                                    {{ __('¿ Olvidó su contraseña ?') }}
                                </a>
                            @endif
                        </div> --}}

                        <!-- Auth Buttton -->
                        <button type="submit"
                            class="w-full py-3 mt-12 bg-gray-800 rounded
                            font-medium text-white uppercase
                            focus:outline-none hover:bg-gray-700 hover:shadow-none">
                            Ingresar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
