@props(['name', 'placeholder'])
<div class="form-floating mb-3">
    {{ $slot }}
    <label for="{{ $name }}">{{ $placeholder }}</label>
    <x-form.input-feedback name="{{ $name }}" />
</div>
