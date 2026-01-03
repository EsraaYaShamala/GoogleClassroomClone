<x-alert name="success" class="alert-success" />
<x-alert name="error" class="alert-danger" />

<x-form.floating-control name="title" placeholder="Post Title">
    <x-form.input name="title" :value="$post->title" placeholder="Post Title" />
</x-form.floating-control>

<x-form.floating-control name="content" placeholder="Content">
    <x-form.input-text-area name="content" :value="$post->content" placeholder="Content" />
</x-form.floating-control>

<x-form.floating-control name="topic_id" placeholder="Topic (optional)">
    <select class="form-select" name="topic_id" id="topic_id">
        <option value="">No Topic</option>
        @forelse($classroom->topics as $topic)
            <option value="{{ $topic->id }}" @selected($post->topic_id == $topic->id)>
                {{ $topic->name }}
            </option>
        @empty
            <option value="">No topics available</option>
        @endforelse
    </select>
</x-form.floating-control>

<x-form.floating-control name="file[]" placeholder="Upload New Files:">
    <x-form.input type="file" name="file[]" id="file" multiple />
</x-form.floating-control>

<button type="submit" class="btn btn-primary">{{ $button_label }}</button>
