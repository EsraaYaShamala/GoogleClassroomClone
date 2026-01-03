@props(['name'])
@if (session()->has($name))
    <div {{ $attributes->class(['alert']) }}>
        {{ session()->get($name) }}
    </div>
@endif
