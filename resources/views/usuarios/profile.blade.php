<x-app-layout>

    <x-slot name="title">Heladerías - Perfil usuario</x-slot>

    <div class="flex mt-32">
        <div class="mx-auto w-full md:w-4/6 bg-gray-100 shadow-lg sm:rounded-lg opacity-85">
            <div class="pl-8 pt-6 text-gray-700 text-3xl font-bold">
                Perfil usuario
            </div>
            <div>
                <hr class="mt-3 mb-4 mx-4 border-gray-500">
            </div>

            <!-- Validation Errors (Usando el componente validation-errors.blade.php) -->
            <x-validation-errors />
            <x-success-message />

            <form method="POST" action="{{ route('profile.update') }}">
                @method('PUT')
                @csrf
                <div class="grid grid-cols-2 gap-4 mx-6 mb-4">
                    <div class="grid grid-rows-2 gap-6">
                        <div>
                            <x-label for="name" :value="__('Nombre')" />
                            <x-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{ auth()->user()->name }}" autofocus />
                        </div>
                        <div>
                            <x-label for="email" :value="__('Email')" />
                            <x-input id="email" class="block mt-1 w-full" type="email" name="email" value="{{ auth()->user()->email }}" autofocus />
                        </div>
                    </div>
                    <div class="grid grid-rows-2 gap-6">
                        <div>
                            <x-label for="new_password" :value="__('Nueva contraseña')" />
                            <x-input id="new_password" class="block mt-1 w-full"
                                     type="password"
                                     name="password"
                                     autocomplete="new-password" />
                        </div>
                        <div>
                            <x-label for="confirm_password" :value="__('Confirme contraseña')" />
                            <x-input id="confirm_password" class="block mt-1 w-full"
                                     type="password"
                                     name="password_confirmation"
                                     autocomplete="confirm-password" />
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end mb-5">
                    <div class="flex justify-end mt-4 mr-6">
                        <x-button class="mr-4">
                            Actualizar
                        </x-button>
                        <x-link-cancel href="/dashboard">
                            Cancelar                    
                        </x-link-cancel>
                    </div>
                </div>

            </form>

        </div>
    </div>

</x-app-layout>
