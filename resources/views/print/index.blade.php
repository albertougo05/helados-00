<x-app-layout>
    <x-slot name="title">Heladerías - Prueba print</x-slot>
    <div class="flex mt-8 mb-6">
        <div class="mx-auto w-3/4 bg-gray-100 overflow-hidden shadow-lg sm:rounded-lg opacity-83">
            <div class="pl-8 pt-6 text-gray-700 text-3xl font-bold">
                Prueba impresión en windows 
            </div>
            <!-- Línea y botones confirma / cancela  -->
            <div>
                <hr class="my-6 mx-4 border-gray-400">
            </div>
            <div class="flex justify-center mt-0 mb-3">
                <div>
                    <x-label for="nombreImp" :value="__('Nombre impresora')" />
                    <x-input id="nombreImp" class="pl-1" placeholder="EPSON_TM-T20III"/>
                </div>         
            </div>
            <div class="flex justify-end mt-0 mb-6 mr-6">
                <div>
                    <x-button id="btnImprimir"
                              class="bg-green-700 font-bold hover:bg-green-700 active:bg-green-900">
                        Imprimir
                    </x-button>
                </div>
            </div>

        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('js/print/ConectorJavaScript.js') }}"></script>
        {{-- <script src="{{ asset('js/print/plugin_impresora_termica.js') }}"></script> --}}
        <script src="{{ asset('js/print/print.js') }}"></script>
    @endpush

</x-app-layout>
