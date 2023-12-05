@props(['disabled' => false])

<select {{ $disabled ? 'disabled' : '' }} 
        {!! $attributes->merge(['class' => 'w-full pt-1 h-8 pl-3 pr-6 text-base placeholder-gray-600 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50appearance-none focus:shadow-outline']) !!}>
    {{ $options }}
</select>

<!-- class="w-full h-10 pl-3 pr-6 text-base placeholder-gray-600 border rounded-lg appearance-none focus:shadow-outline" placeholder="Regular input"> 

'mt-1 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50'
-->