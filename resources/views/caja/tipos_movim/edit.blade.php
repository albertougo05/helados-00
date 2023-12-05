<x-app-layout>
    <x-slot name="title">Heladerías - Tipo Movim.</x-slot>
    <div class="flex mt-8 mb-6">
        <div class="mx-auto w-full md:w-5/6 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-83">
            <div class="pl-8 pt-6 text-gray-700 text-3xl font-bold">
                Tipo movimiento de caja
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
                  action="{{ route('caja_tipos_movim.update', $tipo->id) }}"
                  autocomplete="off">
                @csrf
                @method('PUT')

                <!-- GRID 1 -->
                <div class="grid overflow-auto grid-cols-6 gap-3 px-6 pb-3">
                    <div></div>
                    <div>   <!-- Row 1 Col 1 -->
                        <x-label for="id" :value="__('Id')"/>
                        <x-input id="id"
                                name="id"
                                class="block mt-0 w-3/4 h-9 text-right" 
                                type="text" 
                                value="{{ $tipo->id }}" 
                                readonly />
                    </div>
                    <div class="col-span-3">   <!-- Row 1 Col 2/3 -->
                        <x-label for="descripcion" :value="__('Descripción')" />
                        <x-input type="text"
                                    name="descripcion"
                                    id="descripcion"
                                    autocomplete="off"
                                    value="{{ $tipo->descripcion }}" 
                                    class="block mt-0 w-full h-9" />
                    </div>
                    <div></div>
                </div>

                <!-- GRID 2 -->
                <div class="grid overflow-auto grid-cols-6 gap-3 px-6 pb-3">
                    <div></div>
                    <div class="col-span-2">   <!-- Row 2 Col 3 -->
                        <x-label for="tipo_movim_id" :value="__('Tipo de movimiento')" />
                        <x-select id="tipo_movim_id" name="tipo_movim_id" class="w-full h-9 mt-0">
                            <x-slot name="options">
                                <option value="4" {{ $tipo->tipo_movim_id == 4 ? 'selected' : '' }}>
                                    Egreso
                                </option>
                                <option value="5" {{ $tipo->tipo_movim_id == 5 ? 'selected' : '' }}>
                                    Ingreso
                                </option>
                            </x-slot>
                        </x-select>
                    </div>
                    <div>   <!-- Row 1 Col 1 -->
                        <x-label for="estado" :value="__('Estado')" />
                        <x-select id="estado" name="estado" class="w-full h-9 mt-0">
                            <x-slot name="options">
                                <option value="1" {{ $tipo->estado == 1 ? 'selected' : '' }}>
                                    Activo
                                </option>
                                <option value="0" {{ $tipo->estado == 0 ? 'selected' : '' }}>
                                    Baja
                                </option>
                            </x-slot>
                        </x-select>
                    </div>
                    <div></div>
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
                            <x-link-cancel href="{{ route('caja_tipos_movim.index') }}">
                                Salir                    
                            </x-link-cancel>
                        </div>
                    </div>
                </div> 
            </form>

        </div>

    </div>
</x-app-layout>
