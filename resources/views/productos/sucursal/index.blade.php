<x-app-layout>
    <x-slot name="title">Heladerías - Actualiza precios</x-slot>

    <div class="flex mt-8 mb-6">
        <div class="mx-auto w-full md:w-11/12 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-85">
            <div class="pl-8 pt-6 text-gray-700 text-3xl font-bold">
                Actualiza precios sucursal:
            </div>
            <div class="pt-4 flex justify-center">
                <p class="text-red-800 text-3xl font-bold">{{ $sucursal->nombre }}</p> 
            </div>
            <div>
                <hr class="mt-4 mb-5 mx-4 border-gray-500">
            </div>

            <div class="py-4 flex justify-center">
                <button class="px-10 py-3 text-lg text-blue-100 font-semibold transition-colors duration-300 bg-blue-500 rounded-full shadow-xl hover:bg-blue-600 shadow-blue-400 disabled:opacity-75"
                    id="btnActualizar">
                    Actualizar precios
                </button>
            </div>

            <div>
                <hr class="mt-4 mb-5 mx-4 border-gray-500">
            </div>

            <div class="flex justify-between mt-0">
                <div>
                    <div class="hidden" id="spinGuardando">
                        <div class="flex ml-10">
                            <div class="w-full ml-6">
                                <p>Actualizando precios (no cierre esta ventana !)</p>
                            </div>
                            <div class="w-32 mx-auto">
                                <div style="border-top-color:transparent"
                                    class="w-8 h-8 border-4 border-blue-400 border-solid rounded-full animate-spin"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botón Salida (cancelar) -->
                <div class="flex justify-end px-8 mb-5">
                    <x-link-salir href="{{ route('dashboard') }}" id="btnSalir">
                        Salir
                    </x-link-salir>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            var _sucursal = @json($sucursal);

            @if (session('status'))
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: '{{ session('status') }}',
                    showConfirmButton: false,
                    timer: 5000
                    });
            @endif
        </script>
        <script src="{{ asset('js/productos/sucursal/index.js') }}?ver=0.0.{{ rand() }}"></script>
    @endpush

</x-app-layout>
