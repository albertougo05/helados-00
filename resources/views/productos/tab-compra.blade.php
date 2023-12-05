<div class="grid overflow-auto grid-cols-6 grid-rows-2 gap-x-2 gap-y-1 px-3 py-1">
    <div class="col-span-2">    <!-- Row 1 / Col 1-2 -->
        <x-label for="proveedor_id" :value="__('Proveedor')" />
        <x-select id="proveedor_id" name="proveedor_id" class="w-full h-9 mt-0">
            <x-slot name="options">
                <option value=0 selected>Seleccione proveedor...</option>
                @foreach ($proveedores as $prov)
                    <option value="{{ $prov->id }}"
                        @if($isEdit)
                            {{ ($prov->id == $producto->proveedor_id) ? ' selected' : '' }}
                        @endif
                        >{{ $prov->firma }}
                    </option>
                @endforeach
            </x-slot>
        </x-select>
    </div>
    <div class="ml-6">    <!-- Row 1 / Col 5 -->
        <x-label for="costo_x_unidad" :value="__('Costo x unid.')" />
        @if($isEdit)
            <x-input type="text" 
                name="costo_x_unidad" 
                id="costo_x_unidad" 
                class="mt-0 w-4/5 h-9 text-right" 
                value="{{ $producto->costo_x_unidad }}" />
        @else
            <x-input type="text" 
                    name="costo_x_unidad" 
                    id="costo_x_unidad" 
                    class="mt-0 w-4/5 h-9 text-right" 
                    :value="old('costo_x_unidad')" />
        @endif
    </div>
    <div></div>
    <div>
        <x-label for="costo_x_bulto" :value="__('Costo x bulto')" />
        @if($isEdit)
            <x-input type="text" 
                name="costo_x_bulto" 
                id="costo_x_bulto" 
                class="mt-0 w-4/5 h-9 text-right" 
                value="{{ $producto->costo_x_bulto }}" />
        @else
            <x-input type="text" 
                    name="costo_x_bulto" 
                    id="costo_x_bulto" 
                    class="mt-0 w-4/5 h-9 text-right" 
                    :value="old('costo_x_bulto')" />
        @endif
    </div>
    <div class="col-span-2">   <!-- Row 2 / Col 1-2 -->
        <x-label for="selArticuloIndiv" :value="__('Artículo individual (en caja)')" />
        @if ($isEdit)
            <x-select id="selArticuloIndiv" name="selArticuloIndiv" class="h-9 w-full">
                <x-slot name="options">
                    <option value="0" selected>Seleccione artículo...</option>
                    @foreach ($productos as $prod)
                        <option value="{{ $prod->codigo }}" 
                            {{ ($prod->codigo == $producto->articulo_indiv_id) ? ' selected' : '' }}>
                            {{ $prod->descripcion }}
                        </option>
                    @endforeach
                </x-slot>
            </x-select>
        @else
            <x-select id="selArticuloIndiv" name="selArticuloIndiv" class="h-9 w-full disabled:opacity-65">
                <x-slot name="options">
                    <option value="0" selected>Seleccione artículo...</option>
                    @foreach ($productos as $prod)
                        <option value="{{ $prod->codigo }}" 
                            @if(old('articulo_indiv_id') == $prod->codigo) ' selected' @endif>
                            {{ $prod->descripcion }}
                        </option>
                    @endforeach
                </x-slot>
            </x-select>
        @endif
    </div>
    <div class="ml-6">    <!-- Row 2 / Col 3 -->
        <x-label for="unidades_x_caja" :value="__('Unid. x caja')" />
        @if ($isEdit)
            <x-input type="text" 
                name="unidades_x_caja" 
                id="unidades_x_caja" 
                class="block mt-0 w-4/5 h-9 text-right" 
                value="{{ $producto->unidades_x_caja }}"/>
        @else
            <x-input type="text" 
                name="unidades_x_caja" 
                id="unidades_x_caja" 
                class="block mt-0 w-4/5 h-9 text-right" 
                :value="old('unidades_x_caja')" 
                readonly />
        @endif
    </div>
    <div>   <!-- Row 2 / Col 4 -->
        <x-label for="cajas_x_bulto" :value="__('Cajas x bulto')" />
        @if($isEdit)
            <x-input type="text"
                name="cajas_x_bulto" 
                id="cajas_x_bulto" 
                class="mt-0 w-4/5 h-9 text-right"
                value="{{ $producto->cajas_x_bulto }}"/>
        @else
            <x-input type="text"
                name="cajas_x_bulto" 
                id="cajas_x_bulto" 
                class="mt-0 w-4/5 h-9 text-right"
                :value="old('cajas_x_bulto')"
                readonly />
        @endif
    </div>
    <div>   <!-- Row 2 / Col 5 -->
        <x-label for="unidades_x_bulto" :value="__('Unid. x bulto')" />
        @if($isEdit)
            <x-input type="text"
                name="unidades_x_bulto" 
                id="unidades_x_bulto" 
                class="mt-0 w-4/5 h-9 text-right"
                value="{{ $producto->unidades_x_bulto }}" />
        @else
            <x-input type="text"
                name="unidades_x_bulto" 
                id="unidades_x_bulto" 
                class="mt-0 w-4/5 h-9 text-right"
                :value="old('unidades_x_bulto')" />
        @endif
    </div>
</div>
