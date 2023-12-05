<div class="grid overflow-auto grid-cols-6 grid-rows-2 gap-x-2 gap-y-1 px-3 py-1">
    @if($isEdit)
        <div>    <!-- Row 2 Col 1 -->
            <x-label for="precio_lista_1" :value="__('Precio sugerido')" />
            <x-input type="text" 
                        name="precio_lista_1" 
                        id="precio_lista_1" 
                        class="mt-0 w-3/4 h-9 text-right" />
        </div>
        <div>    <!-- Row 2 Col 2 -->
            <x-label for="precio_lista_2" :value="__('Precio lista 2')" />
            <x-input type="text" 
                        name="precio_lista_2" 
                        id="precio_lista_2" 
                        class="mt-0 w-3/4 h-9 text-right" />
        </div>
        <div>     <!-- Row 2 Col 3 -->
            <x-label for="precio_lista_3" :value="__('Precio lista 3')" />
            <x-input type="text" 
                        name="precio_lista_3" 
                        id="precio_lista_3" 
                        class="mt-0 w-3/4 h-9 text-right" />
        </div>
    @else
        <div>    <!-- Row 2 Col 1 -->
            <x-label for="precio_lista_1" :value="__('Precio lista 1')" />
            <x-input type="text" 
                        name="precio_lista_1" 
                        id="precio_lista_1" 
                        class="mt-0 w-3/4 h-9 text-right" 
                        :value="old('precio_lista_1')" />
        </div>
        <div>    <!-- Row 2 Col 2 -->
            <x-label for="precio_lista_2" :value="__('Precio lista 2')" />
            <x-input type="text" 
            name="precio_lista_2" 
            id="precio_lista_2" 
            class="mt-0 w-3/4 h-9 text-right" 
            :value="old('precio_lista_2')" />
        </div>
        <div>     <!-- Row 2 Col 3 -->
            <x-label for="precio_lista_3" :value="__('Precio lista 3')" />
            <x-input type="text" 
                    name="precio_lista_3" 
                    id="precio_lista_3" 
                    class="mt-0 w-3/4 h-9 text-right" 
                    :value="old('precio_lista_3')" />
        </div>
    @endif
</div>
