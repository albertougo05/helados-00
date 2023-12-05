<x-app-layout>
    <x-slot name="title">Heladerías - Abre turno</x-slot>
    <div class="flex mt-8 mb-6">
        <div class="mx-auto w-full md:w-5/6 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-83">
            <div class="pl-8 pt-6 text-gray-700 text-3xl font-bold">
                NO hay turno de caja abierto...
            </div>
            @if (session('status'))
                <x-alert-success titulo="Perfecto !" >
                    {{ session('status') }}
                </x-alert-success>
            @endif
            <div>
                <hr class="my-3 mx-4 border-gray-400">
            </div>

            <!-- GRID 1 -->
            <div class="flex justify-center mt-10">
                <button type="button" 
                    id="btnAbrir"
                    class="group relative w-2/3 flex justify-center mb-5 py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Abrir turno de caja
                </button>
            </div>

            <!-- Línea y botones confirma / cancela  -->
            <div>
                <hr class="my-6 mx-4 border-gray-400">
            </div>

            <div class="flex justify-end mt-0 mb-6 mr-6">
                <div>
                    <x-link-salir href="{{ route('dashboard') }}">
                        Salir
                    </x-link-salir>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            // Envia a abrir turno caja
            const btnAbrir = document.querySelector('#btnAbrir');
            btnAbrir.addEventListener('click', function (e){
                window.location.assign("{{ route('turno.create') }}");
            });
        </script>
    @endpush

</x-app-layout>
