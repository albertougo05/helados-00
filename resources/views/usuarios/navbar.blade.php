<div class="w-full text-gray-50 color-bootstrap xl:h-14 lg:h-16">
    <div x-data="{ open: false }" class="flex flex-col max-w-screen-xl px-4 mx-auto md:items-center md:justify-between md:flex-row md:px-6 lg:px-8">
        <div class="flex flex-row items-center justify-between p-2">

            <button class="rounded md:hidden focus:outline-none focus:shadow-outline" @click="open = !open">
                <svg fill="currentColor" viewBox="0 0 20 20" class="w-6 h-6">
                    <path x-show="!open" fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM9 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    <path x-show="open" fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
        <nav class="flex-col flex-grow hidden md:pb-0 md:flex md:justify-center md:flex-row md:mt-4">

            <div class="relative mr-1">
                <a class="text-gray-100 hover:bg-gray-100 hover:text-gray-700 px-3 py-2 rounded text-base font-medium"
                    href="#">
                    Buscar
                </a>
            </div>

            <div class="relative">
                <a class="text-gray-100 hover:bg-gray-100 hover:text-gray-700 px-3 py-2 rounded text-base font-medium"
                    href="{{ route('dashboard') }}">
                    Salir
                </a>
            </div>

        </nav>
    </div>
</div>
