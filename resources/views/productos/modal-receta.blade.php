<!-- componente MODAL ingreso productos Receta -->
    <div class="modalReceta hidden">
        <div class="h-screen w-full fixed left-0 top-0 flex justify-center items-center bg-black bg-opacity-50">
            <!-- modal -->
            <div class="bg-white rounded-md ring-2 ring-offset-2 ring-blue-600 shadow-lg w-10/12 md:w-1/3">
                <!-- modal header  - border-2 border-blue-500 - --> 
                <div class="border-b px-4 py-3 flex justify-between items-center">
                    <h2 id="tituloModal" class="font-semibold text-lg">Ingrese producto a receta</h2>
                    <button class="text-black close-modal">&cross;</button>
                </div>

                <!-- modal body -->
                <div class="p-7 flex flex-col">
                    <div>
                        <x-label for="selProductoReceta" :value="__('Producto')"/>
                        <x-select id="selProductoReceta" class="h-8 w-full">
                            <x-slot name="options">
                                <option value="0">Seleccione producto...</option>
                                @foreach ($productosReceta as $prod)
                                    <option value="{{ $prod->codigo }}">
                                        {{ $prod->descripcion }}
                                    </option>
                                @endforeach
                            </x-slot>
                        </x-select>
                    </div>
                    <div>
                        <x-label for="modalCosto" :value="__('Costo')" class="mt-3"/>
                        <x-input type="text" 
                                 id="modalCosto" 
                                 class="block w-1/2 h-8 text-right"/>
                    </div>
                    <div>
                        <x-label for="modalCantidad" :value="__('Cantidad')" class="mt-3"/>
                        <x-input type="text" 
                                 id="modalCantidad" 
                                 class="block w-1/2 h-8 text-right"/>
                    </div>
                </div>

                <!-- modal footer -->
                <div class="flex justify-end items-center w-100 border-t p-5">
                    <button class="px-3 py-1 mr-3 bg-gray-200 border border-transparent rounded text-gray-800 hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 close-modal">
                        Cancela
                    </button>
                    <button id="btnEliminaProdReceta"
                        class="hidden px-3 py-1 mr-3 bg-red-600 border border-transparent rounded text-white hover:bg-red-800 active:bg-red-600 focus:outline-none focus:border-red-600 focus:ring ring-red-600 disabled:opacity-25 transition ease-in-out duration-150">
                        Elimina
                    </button>
                    <button class="px-3 py-1 bg-blue-500 hover:bg-blue-700 rounded text-white"
                            id="btnConfirmaModal">
                        Confirma
                    </button>
                </div>
            </div>
        </div>
    </div>
