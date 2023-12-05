<x-app-layout>
    <x-slot name="title">Heladerías - Selecciona sucursal</x-slot>
    <div class="flex mt-8 mb-6">
        <div class="mx-auto w-full md:w-2/3 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-83">
            <div class="pl-8 pt-6 text-gray-700 text-3xl font-bold">
                Selecciona sucursal
            </div>
            @if (session('status'))
                <x-alert-success titulo="Perfecto !" >
                    <strong>{{ session('status') }}</strong>
                </x-alert-success>
            @else
                <x-alert-success titulo="Recuerde !" >
                    Usted está ahora, en sucursal: {{ $sucursal->nombre }}
                </x-alert-success>
            @endif
            <div>
                <hr class="my-3 mx-4 border-gray-400">
            </div>
            <form method="POST" 
                  action="{{ route('selecciona.sucursal') }}" 
                  autocomplete="off">
                @csrf

                <!-- GRID 1 -->
                <div class="grid overflow-auto grid-cols-5 grid-rows-1 gap-4 px-6 pb-3">
                    <div>   <!-- Row 1 Col 1 -->
                    </div>
                    <div class="col-span-3">   <!-- Row 1 Col 2 -->
                        <x-label for="sucursal_id" :value="__('Sucursales')" />
                        <x-select id="sucursal_id" name="sucursal_id" class="w-full h-9 mt-0">
                            <x-slot name="options">
                                <option value=0>Seleccione ...</option>
                                @foreach ($sucursales as $suc)
                                    <option value="{{ $suc->id }}">
                                        {{ $suc->nombre }}
                                    </option>
                                @endforeach
                            </x-slot>
                        </x-select>
                    </div>
                </div>

                <!-- Línea y botones confirma / cancela  -->
                <div>
                    <hr class="my-6 mx-4 border-gray-400">
                </div>
                <div class="flex justify-between mt-0">
                    <div>
                        <div class="hidden" id="spinGuardando">
                            <div class="flex ml-10">
                                <div class="w-48 ml-6">
                                    <p>Guardando datos...</p>
                                </div>
                                <div class="w-32 mx-auto">
                                    <div style="border-top-color:transparent"
                                        class="w-8 h-8 border-4 border-blue-400 border-solid rounded-full animate-spin"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end mt-0 mb-6 mr-6">
                        <div>
                            @if (!session('status'))
                                <x-button class="mr-4" id="btnConfirma">
                                    Confirma
                                </x-button>
                            @endif
                            <x-link-salir href="{{ route('dashboard') }}">
                                Salir
                            </x-link-salir>
                        </div>
                    </div>
                </div> 
            </form>
        </div>
    </div>
</x-app-layout>
