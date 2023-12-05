@props(['disabled' => false])

<div class="flex flex-row">
    <span class="flex items-center bg-grey-lighter rounded rounded-r-none px-3 font-bold text-grey-darker border-gray-300">$</span>
    <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50']) !!}>
</div>
