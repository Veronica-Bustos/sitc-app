@props(['name', 'label' => null, 'options' => [], 'value' => '', 'placeholder' => null, 'required' => false])

<div>
    @if ($label)
        <label for="{{ $name }}" class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    <select name="{{ $name }}" id="{{ $name }}"
        {{ $attributes->merge([
            'class' =>
                'w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent',
        ]) }}>
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $key => $option)
            <option value="{{ $key }}" {{ $value == $key ? 'selected' : '' }}>
                {{ $option }}
            </option>
        @endforeach
    </select>
    @error($name)
        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
    @enderror
</div>
