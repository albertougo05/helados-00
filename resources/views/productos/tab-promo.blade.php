<div class="grid overflow-auto grid-cols-4 grid-rows-1 gap-6 px-3 my-1">
    <div> <!-- Row 1 / Col 1 -->
        <x-label for="promo_desde" :value="__('Desde')" class="mb-0"/>
        <x-input id="promo_desde"
                name="promo_desde"
                class="h-10 w-full mb-1 pt-1" 
                type="date" />
    </div>
    <div> <!-- Row 1 / Col 2 -->
        <x-label for="promo_hasta" :value="__('Hasta')" class="mb-0"/>
        <x-input id="promo_hasta"
                name="promo_hasta"
                class="h-10 w-full mb-1 pt-1"
                type="date" />
    </div>
    <div class=""></div>
    <div class=""></div>
</div>

<div class="grid overflow-auto grid-cols-8 grid-rows-1 gap-x-2 p-2 mt-3" id="dias_promo">
    <div class="flex">
        <input type="checkbox"
               id="promo_todos"
               class="block ml-3" 
               value="todos" />
        <x-label for="promo_todos" :value="__('Todos')" />
    </div>
    <div class="flex">
        <input type="checkbox"
               id="promo_lunes"
               name="dias_promo"
               class="block ml-3" 
               value="1"/>
        <x-label for="promo_lunes" :value="__('Lunes')" />
    </div>
    <div class="flex">
        <input type="checkbox"
               name="dias_promo"
               id="promo_martes"
               class="block ml-4" 
               value="2" />
        <x-label for="promo_martes" :value="__('Martes')" />
    </div>
    <div class="flex">
        <input type="checkbox"
               name="dias_promo"
               id="promo_miercoles"
               class="block ml-4"
               value="3" />
        <x-label for="promo_miercoles" :value="__('Miércoles')" />
    </div>
    <div class="flex">
        <input type="checkbox"
               name="dias_promo"
               id="promo_jueves"
               class="block ml-5"
               value="4" />
        <x-label for="promo_jueves" :value="__('Jueves')" />
    </div>
    <div class="flex">
        <input type="checkbox"
               name="dias_promo"
               id="promo_viernes"
               class="block ml-5"
               value="5" />
        <x-label for="promo_viernes" :value="__('Viernes')" />
    </div>
    <div class="flex">
        <input type="checkbox"
               name="dias_promo"
               id="promo_sabado"
               class="block ml-6"
               value="6" />
        <x-label for="promo_sabado" :value="__('Sábado')" />
    </div>
    <div class="flex">
        <input type="checkbox"
               name="dias_promo"
               id="promo_domingo"
               class="block ml-6" 
               value="0" />
        <x-label for="promo_domingo" :value="__('Domingo')" />
    </div>
</div>
<div class="pl-8 mt-3 text-gray-700 text-lg font-bold">
    Artículos incluídos
</div>
<div>
    <hr class="mb-3 mt-1 mx-4 border-gray-400">
</div>
<div class="flex">
    <button 
        type="button"
        class="h-7 px-3 ml-5 my-1 inline-flex items-center bg-green-600 border border-transparent rounded font-semibold text-base text-white hover:bg-green-800 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150"
        id="btnAgregarArticulo">
        <span>
            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
        </span>
        Agregar artículo
    </button>
</div>
<div class="bg-white shadow rounded overflow-x-auto mt-2 mx-5">
    <table class="table-fixed min-w-full md:rounded" id="tableArticulosPromo">
        <thead class="text-sm bg-indigo-800 text-gray-200">
            <tr>
                <th scope="col" class="py-2 text-center">Combo</th>
                <th scope="col" class="py-2 text-center">Código</th>
                <th scope="col" class="py-2 text-center w-1/2">Descripción</th>
                <th scope="col" class="pr-1 py-2 text-right">Cant.</th>
                <th scope="col" class="pr-1 py-2 text-right">Precio</th>
                <th scope="col" class="pr-2 py-2 text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200"
               id="bodyTablePromo">
        </tbody>
    </table>
</div>
<div class="flex justify-between mx-5 mt-1">
    <div>
        {{-- 
            <p class="text-sm mt-2 ml-1">Suma total $ <span id="totalPromo" class="font-medium">0,00</span></p> 
        --}}
    </div>
    <div id="divPrecioPromo">
        <x-label for="precioPromo" :value="__('Precio promo')"/>
        <x-input type="text" 
                 id="precioPromo" 
                 class="h-8 w-40 text-right"/>
    </div>
</div>

<div>
    <hr class="mb-3 mt-2 mx-4 border-gray-400">
</div>
<div id="divConfirmaPromo">
    <div class="flex justify-between mr-5">
        <div>
            <div id="spinGuardandoPromo" class="hidden">
                <div class="flex ml-10">
                    <div class="w-48 ml-6">
                        <p>Guardando datos...</p>
                    </div>
                    <div class="w-32 mx-auto">
                        <div style="border-top-color:transparent"
                            class="w-8 h-8 border-4 border-blue-500 border-solid rounded-full animate-spin"></div>
                    </div>
                </div>
            </div>
        </div>
        <button type="button"
                class="h-8 py-1 px-2 inline-flex items-center bg-blue-500 border border-transparent rounded font-semibold text-base text-white hover:bg-blue-700 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-65 disabled:cursor-not-allowed transition ease-in-out duration-150"
                id="btnConfirmaPromo">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </span>
            Confirma promoción
        </button>
    </div>
</div>