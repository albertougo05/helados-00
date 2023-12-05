<!-- componente MODAL ingreso articulos Promocion -->
    <div id="modalArticuloPromo" class="hidden">
        <div class="h-screen w-full fixed left-0 top-0 flex justify-center items-center bg-black bg-opacity-50">
            <!-- modal PROMO -->
            <div id="divPromo" class="bg-orange-200 rounded-md ring-2 ring-blue-700 shadow-lg w-10/12 md:w-1/3">
                <!-- modal header  - border-2 border-blue-500 - --> 
                <div class="border-b border-slate-600 px-4 py-3 flex justify-between items-center">
                    <h2 id="tituloModalPromo" class="font-semibold text-lg">Ingrese artículo a promoción</h2>
                    <button class="text-black close-modal-promo">&cross;</button>
                </div>

                <!-- modal body -->
                <div class="p-6 flex flex-col">
                    <div class="mb-3 mt-0">
                        <x-label for="selComboPromo" :value="__('Combo Nro.')"/>
                        <x-select id="selComboPromo" class="h-8 w-full">
                            <x-slot name="options">
                                <option value="1">Combo 1</option>
                                <option value="2">Combo 2</option>
                                <option value="3">Combo 3</option>
                                <option value="4">Combo 4</option>
                                <option value="5">Combo 5</option>
                                <option value="6">Combo 6</option>
                            </x-slot>
                        </x-select>
                    </div>
                    <div>
                        <x-label for="selArtPromo" :value="__('Artículo')"/>
                        <x-select id="selArtPromo" class="h-8 w-full">
                            <x-slot name="options">
                                <option value="0">Seleccione producto...</option>
                                @foreach ($articulosPromo as $artic)
                                    @if ($artic->codigo != $producto->codigo)
                                        <option value="{{ $artic->codigo }}">
                                            {{ $artic->descripcion }}
                                        </option>
                                    @endif
                                @endforeach
                            </x-slot>
                        </x-select>
                    </div>
                    <div>
                        <x-label for="precioArtPromo" :value="__('Precio')" class="mt-3"/>
                        <x-input type="text" 
                                 id="precioArtPromo" 
                                 class="block w-1/2 h-8 text-right"/>
                    </div>
                    {{-- <div>
                        <x-label for="costoArtPromo" :value="__('Costo')" class="mt-3"/>
                        <x-input type="text" 
                                 id="costoArtPromo" 
                                 class="block w-1/2 h-8 text-right"/>
                    </div> --}}
                    <div>
                        <x-label for="cantArtPromo" :value="__('Cantidad')" class="mt-3"/>
                        <x-input type="text" 
                                 id="cantArtPromo" 
                                 aria-selected="true"
                                 class="block w-1/2 h-8 text-right"/>
                    </div>
                </div>

                <!-- modal footer -->
                <div class="flex justify-end items-center w-100 border-t border-slate-600 p-5">
                    <button class="px-3 py-1 mr-3 bg-gray-200 border border-transparent rounded text-gray-800 hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 close-modal-promo">
                        Cancela
                    </button>
                    <button id="btnEliminaArtPromo"
                        class="hidden px-3 py-1 mr-3 bg-red-600 border border-transparent rounded text-white hover:bg-red-800 active:bg-red-600 focus:outline-none focus:border-red-600 focus:ring ring-red-600 disabled:opacity-25 transition ease-in-out duration-150">
                        Elimina
                    </button>
                    <button class="px-3 py-1 bg-blue-500 hover:bg-blue-700 rounded text-white"
                            id="btnConfirmaArtPromo">
                        Confirma
                    </button>
                </div>
            </div>
        </div>
    </div>
