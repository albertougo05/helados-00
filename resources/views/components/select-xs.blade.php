@props(['disabled' => false])

<select {{ $disabled ? 'disabled' : '' }} 
        {!! $attributes->merge(['class' => 'pt-0.5 h-8 pl-3 pr-6 text-gray-700 text-base placeholder-gray-600 rounded shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50appearance-none focus:shadow-outline']) !!}>
    {{ $options }}
</select>