@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-md font-semibold text-base-content pb-2']) }}>
    {{ $value ?? $slot }}
</label>
