        <!-- cabecera (datos) -->
        <div class="flex justify-between px-6">
            <div class="mt-5 text-gray-700 text-3xl font-bold">
                {{ $titulo }}
            </div>
            <div class="mt-8 mr-1 text-blue-600 text-base font-semibold">
                <p><span class="text-gray-800">Sucursal: </span>{{ $sucursal }}</p>
            </div>
        </div>
        <div>
            <hr class="mt-2 mb-2 mx-4 border-gray-500">
        </div>
        <div class="grid overflow-hidden grid-cols-6 grid-rows-2 gap-x-3 gap-y-2 my-2">
            <div class="col-span-2 ml-5"> <!-- Sucursales -->
                <x-label for="sucursal_id" :value="__('Sucursal')" class="block" />
                <x-select id="sucursal_id" class="w-full h-8" autofocus>
                    <x-slot name="options">
                        @foreach ($sucursales as $sucursal)
                            <option class="bg-gray-300" value="{{ $sucursal->id }}"
                                @if ($sucursal_id == $sucursal->id)
                                    selected
                                @endif>
                                {{ $sucursal->nombre }}
                            </option>
                        @endforeach
                    </x-slot>
                </x-select>
            </div>
            <div class=""> <!-- Tipo de toma stock -->
                <x-label for="tipo_toma_stock" :value="__('Tipo stock')" class="block" />
                <x-select id="tipo_toma_stock" class="w-full h-8">
                    <x-slot name="options">
                        <option class="bg-gray-300" value="parcial">
                            Parcial
                        </option>
                        <option class="bg-gray-300" value="final">
                            Final
                        </option>
                    </x-slot>
                </x-select>
            </div>
            <div></div>
            <div class="">
                <x-label for="fecha_toma_stock" :value="__('Fecha Toma de Stock')"/>
                <x-input id="fecha_toma_stock"
                class="text-right w-full pb-1 pt-0.5"
                type="date"/>
            </div>
            <div class="mr-5">
                <x-label for="hora_toma_stock" :value="__('Hora')"/>
                <x-input id="hora_toma_stock"
                class="text-right w-full"
                type="text"/>
            </div>
            <div class="col-span-2 ml-5 mb-1">
                <x-label for="detalle" :value="__('Detalle')"/>
                <x-input id="detalle" 
                         class="w-full"
                         type="text"/>
            </div>
            <div class="mr-1|">
                <x-label for="totalPlanilla" :value="__('Total $ planilla')"/>
                <input id="totalPlanilla"
                       class="text-right w-full rounded-md h-8 shadow-sm focus:border-indigo-300 border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                       type="text"
                       disabled />
            </div>
            <div></div>
            <div class="">
                <x-label for="fecha_stock_final" :value="__('Fecha stock final')"/>
                <x-input id="fecha_stock_final"
                         class="text-right w-full pb-1 pt-0.5"
                         disabled
                         type="date"/>
                        </div>
            <div class="mr-5">
                <x-label for="hora_stock_final" :value="__('Hora stock final')"/>
                <x-input id="hora_stock_final"
                class="text-right w-full"
                disabled
                type="text"/>
            </div>
        </div>
