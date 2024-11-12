<select name="{{ $name }}" {{ $attributes }} class="text">
    {{ $slot }}  {{-- برای قرار دادن کد های بین تگ های باز و بسته سلکت --}}
</select>
<x-validation-error field="{{ $name }}" />
