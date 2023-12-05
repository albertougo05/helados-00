<a {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded font-semibold text-xs text-gray-100 uppercase tracking-widest hover:bg-gray-700 hover:text-white active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-700 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</a>
