<a {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 hover:text-gray-100 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-400 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</a>
