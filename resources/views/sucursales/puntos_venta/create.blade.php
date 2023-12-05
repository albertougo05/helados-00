<x-app-layout>
    <x-slot name="title">Heladerías - Punto Venta</x-slot>
    <div class="flex mt-8 mb-6">
        <div class="mx-auto w-full md:w-5/6 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-83">
            <div class="pl-8 pt-6 text-gray-700 text-3xl font-bold">
                Nuevo Punto de Venta
            </div>
            @if (session('status'))
                <x-alert-success titulo="Perfecto !" >
                    {{ session('status') }}
                </x-alert-success>
            @endif
            <div>
                <hr class="my-3 mx-4 border-gray-400">
            </div>
            <form method="POST" 
                  action="{{ route('puntos_venta.store') }}" 
                  autocomplete="off">
                @csrf

                <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">
                <input type="hidden" name="id" value="{{ $newid ?? '' }}">
                <!-- GRID 1 -->
                <div class="grid overflow-auto grid-cols-5 grid-rows-1 gap-4 px-6 pb-3">
                    <!-- <div>
                        <x-label for="id" :value="__('Id')"/>
                        <x-input id="id"
                                {{-- name="id" --}}
                                class="block mt-0 w-3/4 h-9 text-right" 
                                type="text" 
                                value="{{ $newid ?? '' }}" 
                                readonly />
                    </div> -->
                    <div class="col-span-2">   <!-- Row 1 Col 2/3 -->
                        <x-label for="nombre" :value="__('Nombre')" />
                        <x-input type="text"
                                    name="nombre"
                                    id="nombre"
                                    autocomplete="off"
                                    :value="old('nombre')"
                                    class="block mt-0 w-full h-9" />
                                    @error('nombre')
                                        <p class="m-1 text-red-600 text-xs italic">{{ $errors->first('nombre') }}</p>
                                    @enderror
                    </div>
                    <div class="col-span-2">   <!-- Row 1 Col 4/ -->
                        <x-label for="empresa" :value="__('Empresa')" />
                        <x-input type="text"
                                    id="empresa"
                                    autocomplete="off"
                                    value="{{ $empresa->razon_social }}"
                                    class="block mt-0 w-full h-9" 
                                    readonly />
                    </div>
                    <div>   <!-- Row 1 Col 5 -->
                        <x-label for="estado" :value="__('Estado')" />
                        <x-select id="estado" name="estado" class="w-full h-9 mt-0">
                            <x-slot name="options">
                                <option value=1>
                                    Activo
                                </option>
                                <option value=0>
                                    Baja
                                </option>
                            </x-slot>
                        </x-select>
                    </div>
                </div>

                <!-- GRID 2 -->
                <div class="grid overflow-auto grid-cols-5 grid-rows-1 gap-4 px-6 pb-1">

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
                            <x-button class="mr-4" id="btnConfirma">
                                Confirma
                            </x-button>
                            <x-link-cancel href="{{ route('puntos_venta.index') }}">
                                Salir                    
                            </x-link-cancel>
                        </div>
                    </div>
                </div> 
            </form>

        </div>

    </div>
</x-app-layout>
