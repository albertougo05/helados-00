<x-app-layout>
    <x-slot name="title">Heladería - Stock Real</x-slot>

    <div class="flex my-3 mx-0">
        <div class="mx-4 md:mx-4 w-full bg-gray-100 overflow-y-auto shadow-lg rounded-lg opacity-85">

            @include('stock.real.cabecera')
            <hr class="my-2 mx-4 border-gray-500">
            @include('stock.real.listados')
            <hr class="m-4 border-gray-500">

            <div class="flex justify-between mt-2 mb-3">
                <div>
                    <div id="spinGuardando" class="hidden">
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
                <div class="flex justify-end mr-6">
                    <x-button class="mr-4" id="btnConfirma">
                        Confirma
                    </x-button>
                    <x-link-cancel href="{{ route('stock.real.planilla.edit', $planilla->id) }}" class="mr-4">
                        Cancela
                    </x-link-cancel>
                    <x-link-salir href="{{  route('stock.real.planilla') }}">
                        Salir
                    </x-link-salir>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            var _REAL = {
                edita: true,
                id: parseInt("{{ $planilla->id }}"),
                planilla: @json($planilla),
                pathStockReal: "{{ route('stock.real.planilla') }}",
                pathDatosPlanilla: "{{ route('stock.real.planilla.store') }}",
                pathDatosArticulos: "{{ route('stock.real.planilla.store_articulos') }}",
                pathDatosHelados: "{{ route('stock.real.planilla.store_helados') }}",
                pathUpdateDatosPlanilla: "{{ route('stock.real.planilla.update', $planilla->id) }}",
                pathUpdateDatosArticulos: "{{ route('stock.real.planilla.updateArticulos', $planilla->id) }}",
                pathUpdateDatosHelados: "{{ route('stock.real.planilla.updateHelados', $planilla->id) }}",
                articulos: @json($articulos),
                articulosPlanilla: @json($articulosPlanilla),
                helados: @json($helados),
                heladosPlanilla: @json($heladosPlanilla),
                peso_envase: parseFloat('{{ $peso_envase }}'),
                usuario_id: parseInt('{{ $usuario_id }}'),
                sucursal_id: parseInt('{{ $sucursal_id }}'),

            };
        </script>
        <script src="{{ asset('/js/stock/real/edit.js') }}?ver=0.0.{{ rand() }}"></script>
    @endpush
    
</x-app-layout>
