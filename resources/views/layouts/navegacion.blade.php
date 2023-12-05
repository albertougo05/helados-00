    <div class="w-full text-gray-50 color-bootstrap xl:h-14 lg:h-16">
        <div x-data="{ open: false }" class="flex flex-col max-w-screen-xl px-4 mx-auto md:items-center md:justify-between md:flex-row md:px-6 lg:px-8">
            <div class="flex flex-row items-center justify-between p-2">
<!--        <img class="sm:h-10" src="{{ asset('img/logo_heladerias_dc.png') }}" alt="logo">

                <a href="#" class="text-lg font-semibold tracking-widest text-white focus:outline-none focus:shadow-outline">
                    Heladerías DC
                </a> -->
                <button class="rounded md:hidden focus:outline-none focus:shadow-outline" @click="open = !open">
                    <svg fill="currentColor" viewBox="0 0 20 20" class="w-6 h-6">
                        <path x-show="!open" fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM9 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        <path x-show="open" fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <nav :class="{'flex': open, 'hidden': !open}" class="flex-col flex-grow hidden pb-4 md:pb-0 md:flex md:justify-end md:flex-row">

                <div @mouseleave="open = false" class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex flex-row items-center w-full px-4 py-4 mt-2  text-gray-300 font-semibold text-left bg-transparent md:w-auto md:inline md:mt-0 md:ml-4 hover:text-white focus:text-white focus:outline-none focus:ring-3">
                        <span>Ventas</span>
                        <svg fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': open, 'rotate-0': !open}" class="inline w-4 h-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 w-full mt-0 origin-top-right rounded-md shadow-lg md:w-48 z-30">
                        <div class="px-2 py-2 bg-white rounded-md shadow w-56">
                            <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                href="{{ route('ventas.comprobante') }}">
                                Venta</a>
                            <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                href="{{ route('ventas.infopedido') }}">
                                Informe pedidos turno</a>
                            @if(Auth::user()->perfil_id < 2) <!-- Muestra si el perfil es 1 (admin) o 2 (admin empresa) -->
                                <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                    href="{{ route('ventas.infoventasprod') }}">
                                    Info Vtas. x producto</a>
                                <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                    href="{{ route('ventas.mix') }}">
                                    Mix de Ventas</a>
                            @endif
                        </div>
                    </div>
                </div>

                <div @mouseleave="open = false" class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex flex-row items-center w-full px-4 py-4 mt-2  text-gray-300 font-semibold text-left bg-transparent md:w-auto md:inline md:mt-0 md:ml-4 hover:text-white focus:text-white focus:outline-none focus:ring-3">
                        <span>Caja</span>
                        <svg fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': open, 'rotate-0': !open}" class="inline w-4 h-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 w-full mt-0 origin-top-right rounded-md shadow-lg md:w-48 z-30">
                        <div class="px-2 py-2 bg-white rounded-md shadow">
                            <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                href="{{ route('turno.index') }}">
                                Apertura/Cierre</a>
                            <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                href="{{ route('caja_movimiento.create') }}">
                                Nuevo movimiento</a>
                            <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                href="{{ route('caja_movimiento.index') }}">
                                Movimientos de caja</a>
                            @if(Auth::user()->perfil_id < 2) <!-- Muestra si el perfil es 1 (admin) o 2 (admin empresa) -->
                                <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                    href="{{ route('movimientos_caja.informe') }}">
                                    Informe movimientos</a>
                                <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                    href="{{ route('turnos.informe') }}">
                                    Informe turnos caja</a>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div @mouseleave="open = false" class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex flex-row items-center w-full px-4 py-4 mt-2  text-gray-300 font-semibold text-left bg-transparent md:w-auto md:inline md:mt-0 md:ml-4 hover:text-white focus:text-white focus:outline-none focus:ring-3">
                        <span>Stock</span>
                        <svg fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': open, 'rotate-0': !open}" class="inline w-4 h-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 w-full mt-0 origin-top-right rounded-md shadow-lg md:w-48 z-30">
                        <div class="px-2 py-2 bg-white rounded-md shadow">
                            <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                href="{{ route('comprobante_stock.index') }}">
                                Ingreso movimiento</a>
                            <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                href="{{ route('stock.infomovimientos') }}">
                                Informe movimientos</a>
                            <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                href="{{ route('stock.informediario') }}">
                                Informe Diario</a>
                            @if(Auth::user()->perfil_id < 2) <!-- Muestra si el perfil es 1 (admin) o 2 (admin empresa) -->
                                <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                    href="{{ route('stock.real.planilla') }}">
                                    Planilla Stock Real</a>
                                <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                    href="{{ route('stock.infodesvio') }}">
                                    Informe de desvío</a>
                            @endif
                        </div>
                    </div>
                </div>

                <div @mouseleave="open = false" class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex flex-row items-center w-full px-4 py-4 mt-2  text-gray-300 font-semibold text-left bg-transparent md:w-auto md:inline md:mt-0 md:ml-4 hover:text-white focus:text-white focus:outline-none focus:ring-3">
                        <span>Productos</span>
                        <svg fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': open, 'rotate-0': !open}" class="inline w-4 h-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 w-full mt-0 origin-top-right rounded-md shadow-lg md:w-48 z-30">
                        <div class="px-2 py-2 bg-white rounded-md shadow">
                            @if(Auth::user()->perfil_id < 2) <!-- Muestra si el perfil es 1 (admin) o 2 (admin empresa) -->
                                <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                    href="{{ route('producto.index') }}">
                                    Actualización</a>
                                <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                    href="{{ route('producto_grupo.index') }}">
                                    Grupos</a>
                                <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                    href="{{ route('producto_tipo.index') }}">
                                    Tipos</a>
                                <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                    href="{{ route('producto.listaprecios') }}">
                                    Lista precios</a>
                            @endif
                            <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                href="{{ route('producto.sucursal.actualiza') }}">
                                Actualiza precios</a>
                            @if (Auth::user()->perfil_id > 2)
                                <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                    href="{{ route('comprobante_stock.index') }}">
                                    Ingreso stock</a>
                            @endif
                        </div>
                    </div>
                </div>

                @if(Auth::user()->perfil_id < 2) <!-- Muestra si el perfil es 1 (admin) o 2 (admin empresa) -->
                    <div @mouseleave="open = false" class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex flex-row items-center w-full px-4 py-4 mt-2  text-gray-300 font-semibold text-left bg-transparent md:w-auto md:inline md:mt-0 md:ml-4 hover:text-white focus:text-white focus:outline-none focus:ring-3">
                            <span>Cuenta cte.</span>
                            <svg fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': open, 'rotate-0': !open}" class="inline w-4 h-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 w-full mt-0 origin-top-right rounded-md shadow-lg md:w-64 z-30">
                            <div class="px-2 py-2 bg-white rounded-md shadow">
                                <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                    href="{{ route('firma.index') }}">
                                    Actualización Firmas/Clientes</a>
                                <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                    href="{{ route('ctasctes.index') }}">
                                    Informe Cta. Cte.</a>
                                {{-- <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                    href="#">
                                    Tarjetas</a>
                                <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                    href="#">
                                    Bancos</a>
                                <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                    href="#">
                                    Cuentas bancarias</a> --}}
                            </div>
                        </div>
                    </div>
                @endif

                @if(Auth::user()->perfil_id < 2) <!-- Muestra si el perfil es 1 (admin) o 2 (admin empresa) -->
                    <div @mouseleave="open = false" class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex flex-row items-center w-full px-4 py-4 mt-2  text-gray-300 font-semibold text-left bg-transparent md:w-auto md:inline md:mt-0 md:ml-4 hover:text-white focus:text-white focus:outline-none focus:ring-3">
                            <span>Configuración</span>
                            <svg fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': open, 'rotate-0': !open}" class="inline w-4 h-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 w-full mt-0 origin-top-right rounded-md shadow-lg md:w-48 z-30">
                            <div class="px-2 py-2 bg-white rounded-md shadow w-56">
                                {{-- <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                    href="#">
                                    Empresa</a> --}}
                                <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                    href="{{ route('sucursal.index') }}">
                                    Sucursales</a>
                                {{-- <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                    href="{{ route('puntos_venta.index') }}">
                                    Puntos de Venta</a> --}}
                                <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                    href="{{ route('caja_tipos_movim.index') }}">
                                    Tipos movim. caja</a>
                                <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                    href="{{ route('stock_tipos_movim.index') }}">
                                    Tipos movim. stock</a>
                                <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                    href="{{ route('usuarios') }}">
                                    Usuarios</a>
                                {{-- <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                    href="{{ route('empleado.index') }}">
                                    Empleados</a> --}}
                                {{-- <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                    href="#">
                                    Perfiles</a> --}}
                                <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                    href="{{ route('parametros') }}">
                                    Parámetros generales</a>
                            </div>
                        </div>
                    </div>
                @endif

                <div @mouseleave="open = false" class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex flex-row items-center w-full px-4 py-4 mt-2  text-gray-300 font-semibold text-left bg-transparent md:w-auto md:inline md:mt-0 md:ml-4 hover:text-white focus:text-white focus:outline-none focus:ring-3">
                        <span>{{ Auth::user()->name }}</span>
                        <svg fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': open, 'rotate-0': !open}" class="inline w-4 h-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 w-full mt-0 origin-top-right rounded-md shadow-lg md:w-48 z-30">
                        <div class="px-2 py-2 bg-white rounded-md shadow">
                            <a class="block px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" 
                                href="{{ route("usuario.changepassw") }}">
                                Cambiar contraseña
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class=" text-left w-full px-3 py-1 mt-1 text-gray-700 font-medium rounded md:mt-0 hover:text-gray-200 focus:text-gray-200 hover:bg-indigo-500 focus:bg-blue-500">
                                    Salir
                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline h-6 w-6 float-right" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </nav>
        </div>
    </div>
