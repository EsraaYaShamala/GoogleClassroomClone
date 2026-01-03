<x-alert name="success" class="alert-success" />
<x-alert name="error" class="alert-danger" />

<x-form.floating-control name="name" placeholder="Class Name">
    <x-form.input name="name" :value="$classroom->name" placeholder="Class Name" />
</x-form.floating-control>

<x-form.floating-control name="section" placeholder="Section">
    <x-form.input name="section" :value="$classroom->section" placeholder="" />
</x-form.floating-control>

<x-form.floating-control name="subject" placeholder="Subject">
    <x-form.input name="subject" :value="$classroom->subject" placeholder="Subject" />
</x-form.floating-control>

<x-form.floating-control name="room" placeholder="Room">
    <x-form.input name="room" :value="$classroom->room" placeholder="Room" />
</x-form.floating-control>

<x-form.floating-control name="cover_image" placeholder="Cover Image">
    @if ($classroom->cover_image_path)
        <img src="{{ Storage::disk('public')->url($classroom->cover_image_path) }}" alt="image">
        <br><br>
    @endif
    <x-form.input type="file" name="cover_image" :value="$classroom->cover_image_path" placeholder="Cover Image" multiple />
</x-form.floating-control>
<button type="submit" class="btn btn-primary">{{ $button_label }}</button>
