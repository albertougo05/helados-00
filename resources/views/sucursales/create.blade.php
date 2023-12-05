<x-app-layout>
    <x-slot name="title">Heladerías - Sucursal</x-slot>
    <div class="flex mt-8 mb-6">
        <div class="mx-auto w-full md:w-5/6 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-83">
            <div class="pl-8 pt-6 text-gray-700 text-3xl font-bold">
                Sucursal
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
                  action="{{ route('sucursal.store') }}" 
                  autocomplete="off">
                @csrf

                <!-- GRID 1 -->
                <div class="grid overflow-auto grid-cols-5 grid-rows-1 gap-4 px-6 pb-3">
                    <div>   <!-- Row 1 Col 1 -->
                        <x-label for="id" :value="__('Id')"/>
                        <x-input id="id"
                                name="id"
                                class="block mt-0 w-3/4 h-9 text-right" 
                                type="text" 
                                value="{{ $newid }}" 
                                readonly />
                    </div>
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
                        <x-label for="empresa_id" :value="__('Empresa')" />
                        <x-select id="empresa_id" name="empresa_id" class="w-full h-9 mt-0">
                            <x-slot name="options">
                                <option value=0 selected>Seleccione empresa...</option>
                                @foreach ($empresas as $empresa)
                                    <option value="{{ $empresa->id }}">
                                        {{ $empresa->razon_social }}
                                    </option>
                                @endforeach
                            </x-slot>
                        </x-select>
                        @error('empresa_id')
                            <p class="m-1 text-red-600 text-xs italic">{{ $errors->first('empresa_id') }}</p>
                        @enderror
                    </div>
                </div>
                <!-- GRID 2 -->
                <div class="grid overflow-auto grid-cols-5 grid-rows-1 gap-4 px-6 pb-3">
                    <div class="col-span-2">   <!-- Row 2 Col 1/2 -->
                        <x-label for="direccion" :value="__('Dirección')" />
                        <x-input type="text"
                                    name="direccion"
                                    id="direccion"
                                    autocomplete="off"
                                    :value="old('direccion')"
                                    class="block mt-0 w-full h-9"/>
                        @error('direccion')
                            <p class="m-1 text-red-600 text-xs italic">{{ $errors->first('direccion') }}</p>
                        @enderror
                    </div>
                    <div>   <!-- Row 2 Col 3 -->
                        <x-label for="localidad" :value="__('Localidad')" />
                        <x-input type="text"
                                    name="localidad"
                                    id="localidad"
                                    autocomplete="off"
                                    :value="old('localidad')"
                                    class="block mt-0 w-full h-9"/>
                        @error('localidad')
                            <p class="m-1 text-red-600 text-xs italic">{{ $errors->first('localidad') }}</p>
                        @enderror
                    </div>
                    <div>   <!-- Row 2 Col 4 -->
                        <x-label for="provincia" :value="__('Provincia')" />
                        <x-input type="text"
                                    name="provincia"
                                    id="provincia"
                                    autocomplete="off"
                                    :value="old('provincia')"
                                    class="block mt-0 w-full h-9"/>
                    </div>
                    <div>   <!-- Row 2 Col 5 -->
                        <x-label for="codigo_postal" :value="__('Código postal')" />
                        <x-input type="text"
                                    name="codigo_postal"
                                    id="codigo_postal"
                                    autocomplete="off"
                                    :value="old('codigo_postal')"
                                    class="block mt-0 w-full h-9"/>
                    </div>
                </div>
                <!-- GRID 3 -->
                <div class="grid overflow-auto grid-cols-5 grid-rows-1 gap-4 px-6 pb-3">
                    <div>   <!-- Row 3 Col 1 -->
                        <x-label for="telefono" :value="__('Teléfono')" />
                        <x-input type="text"
                                    name="telefono"
                                    id="telefono"
                                    autocomplete="off"
                                    :value="old('telefono')"
                                    class="block mt-0 w-full h-9"/>
                    </div>
                    <div>   <!-- Row 3 Col 2 -->
                        <x-label for="celular" :value="__('Celular')" />
                        <x-input type="text"
                                    name="celular"
                                    id="celular"
                                    autocomplete="off"
                                    :value="old('celular')"
                                    class="block mt-0 w-full h-9" />
                    </div>
                    <div class="col-span-2">   <!-- Row 3 Col 3/4 -->
                        <x-label for="email" :value="__('Email')" />
                        <x-input type="text"
                                    name="email"
                                    id="email"
                                    autocomplete="off"
                                    :value="old('email')"
                                    class="block mt-0 w-full h-9" />
                    </div>
                    <div>   <!-- Row 3 Col 5 -->
                        <x-label for="lista_precio" :value="__('Usa lista de precio')" />
                        <x-select id="lista_precio" name="lista_precio" class="w-full h-9 mt-0">
                            <x-slot name="options">
                                <option value=1> 
                                    Lista 1
                                </option>
                                <option value=2>
                                    Lista 2
                                </option>
                                <option value=3>
                                    Lista 3
                                </option>
                            </x-slot>
                        </x-select>
                    </div>
                </div>
                <!-- GRID 4 -->
                <div class="grid overflow-auto grid-cols-5 grid-rows-1 gap-4 px-6 pb-1">
                    <div>   <!-- Row 4 Col 1 -->
                        <x-label for="cant_puntos_venta" :value="__('Cantidad puntos venta')" />
                        <x-select name="cant_puntos_venta" class="w-full h-9 mt-0">
                            <x-slot name="options">
                                <option value="1" selected>
                                    1
                                </option>
                                <!-- <option value="2">
                                    2
                                </option>
                                <option value="3">
                                    3
                                </option> -->
                            </x-slot>
                        </x-select>
                    </div>
                    <div>   <!-- Row 4 Col 2 -->
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
                            <x-link-salir href="{{ route('sucursal.index') }}">
                                Salir                    
                            </x-link-salir>
                        </div>
                    </div>
                </div> 
            </form>

        </div>

    </div>
</x-app-layout>
