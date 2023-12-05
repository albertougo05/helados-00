<x-app-layout>
    <x-slot name="title">Heladerías - Usuario</x-slot>
    <div class="flex mt-12">
            <div class="mx-auto w-full md:w-4/6 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-83">
                <div class="pl-8 pt-6 text-gray-700 text-3xl font-bold">
                    Cambiar contraseña -  <span class="text-2xl text-blue-700">{{ $usuario->name }}</span>
                </div>
                <div>
                    <hr class="mt-3 mb-6 mx-4 border-gray-400">
                </div>
                <form method="POST" id="formPass" action="{{ route('usuario.putchangepassw', $usuario->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-rows-1 grid-cols-1 sm:grid-cols-2 gap-6 mt-4 px-6">
                        <div> <!-- row 2 - col 1 -->
                            <x-label class="text-gray-700" for="password" :value="__('Nueva contraseña')"/>
                            <x-input name="password" 
                                class="form-input w-full mt-1 mr-4 mb-0" 
                                type="password"/>
                        </div>
                        <div> <!-- row 2 - col 2 -->
                            <x-label class="text-gray-700" for="password" :value="__('Confirmar contraseña')" />
                            <x-input name="password_confirmation" 
                                class="form-input w-full mt-1 mr-4 mb-0" 
                                type="password"/>
                            <div class="invisible" id="divNotMatch">
                                <p class="notMatch my-0 text-red-600 text-sm font-semibold italic">Las contraseñas NO coinciden !!</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <hr class="mb-6 mt-2 mx-4 border-gray-400">
                    </div>
                    <div class="flex justify-end mt-4 mb-6 mr-6">
                        @if (session('status'))
                            <div class="alert alert-success mr-4 mt-1 w-64 flex">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(56, 161, 105, 1);transform: ;msFilter:;">
                                    <path d="M19 3H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2zm-7.933 13.481-3.774-3.774 1.414-1.414 2.226 2.226 4.299-5.159 1.537 1.28-5.702 6.841z"></path>
                                </svg>
                                <span class="mx-2">{{ session('status') }}</span>
                            </div>
                        @endif
                        <x-button id="btnConfirma" class="mr-4">
                            Confirmar
                        </x-button>
                        <x-link-salir href="{{ route('dashboard') }}">
                            Salir                    
                        </x-link-salir>
                    </div>
                </form>
            </div>
    </div>

    @push('scripts')
        <script>
            // Variables globales
            const _CHANGE = { 
                _usuario_nombre: "{{ $usuario->name }}",
            };

            const btnConfirma = document.querySelector('#btnConfirma');
            btnConfirma.addEventListener('click', function (e) {
                e.preventDefault();

                const passw = document.querySelector('[name="password"]').value;
                const confirm = document.querySelector('[name="password_confirmation"]').value;
                const divNotMatch = document.querySelector('#divNotMatch');

                if (passw === confirm) {
                    divNotMatch.classList.add('invisible');
                    document.getElementById('formPass').submit();
                } else {
                    divNotMatch.classList.remove('invisible');
                    document.querySelector('[name="password_confirmation"]').focus();
                }
            });
        </script>
    @endpush
</x-app-layout>
