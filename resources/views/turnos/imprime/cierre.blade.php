<x-app-layout>
    <x-slot name="title">Heladerías - Imprime cierre turno</x-slot>
    <div class="modal-espera z-30">
        <div class="modal h-screen w-full fixed left-0 top-0 flex justify-center items-center bg-black bg-opacity-50">
            <!-- modal spin espera -->
            <div class="absolute right-1/2 bottom-1/2  transform translate-x-1/2 translate-y-1/2 ">
                <div style="border-top-color:transparent" class="border-solid animate-spin  rounded-full border-blue-400 border-8 h-24 w-24"></div>
            </div>
            <div class="bg-gray-100 bg-opacity-25 relative p-4 top-28 rounded-md">
                <p class="text-center text-2xl text-white font-semibold">
                    Por favor espere... Guardando cierre e imprimiendo !
                </p>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            // Script para imprimir ticket cierre turno de caja
            var _CIERRE = {
                sucursal_id: "{{ $sucursal_id }}",
                caja_nro: "{{ $caja_nro }}",
                cierre_id: "{{ $cierre_id }}",
                impresora: @json($impresora),
                env: "{{ $env }}",
                pathToEmail: "{{ $pathToEmail }}",
                pathExit: "{{ route('turno.imprime.exit') }}",
            };
        </script>
        <script src="{{ asset('js/turnos/imprime/cierre.js') }}?ver=0.0.{{ rand() }}"></script>
    @endpush
</x-app-layout>