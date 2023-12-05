<x-app-layout>
    <x-slot name="title">Heladerías - No Abre turno</x-slot>
    <div class="flex mt-8 mb-6">
        <div class="mx-auto w-full md:w-5/6 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-83">
            <div class="pl-8 pt-6 text-gray-700 text-3xl font-bold">
                NO puede abrir turno de caja...
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
</x-app-layout>
