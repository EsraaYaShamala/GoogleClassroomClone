<x-alert name="success" class="alert-success" />
<x-alert name="error" class="alert-danger" />

<div class="row">
    <div class="col md-8">
        <x-form.floating-control name="title" placeholder="Title">
            <x-form.input name="title" :value="$classwork->title" placeholder="Title" />
        </x-form.floating-control>

        {{-- <x-form.floating-control name="description" placeholder="Description (optional)"> --}}
        <x-form.input-text-area name="description" :value="$classwork->description" placeholder="Description (optional)" />
        {{-- </x-form.floating-control> --}}
    </div>
    <div class="col md-4">
        <x-form.floating-control name="published_at" placeholder="Published Date">
            <x-form.input name="published_at" :value="$classwork->published_date" type="date" placeholder="Published Date" />
        </x-form.floating-control>
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                {{ $classroom->students->count() }} Students
            </button>
            <ul class="dropdown-menu">
                @foreach ($classroom->students as $student)
                    <li>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="students[]"
                                value="{{ $student->id }}" id="std-{{ $student->id }}" @checked(!isset($assigned) || in_array($student->id, $assigned))>
                            <label class="form-check-label" for="std-{{ $student->id }}">
                                {{ $student->name }}
                            </label>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <input type="hidden" name="type" value="{{ $type }}">
        @if ($type == 'assignment')
            <x-form.floating-control name="options.grade" placeholder="Grade">
                <x-form.input name="options[grade]" :value="$classwork->options['grade'] ?? ''" type="number" min="0"
                    placeholder="Grade" />
            </x-form.floating-control>
            <x-form.floating-control name="options.due_date" placeholder="Due_Date">
                <x-form.input name="options[due_date]" :value="$classwork->options['due_date'] ?? ''" type="date" placeholder="Due_Date" />
            </x-form.floating-control>
        @endif
        <x-form.floating-control name="topic_id" placeholder="Topic (optional)">
            <select class="form-select" name="topic_id" id="topic_id">
                <option value="">No Topic</option>
                @forelse($classroom->topics as $topic)
                    <option value="{{ $topic->id }}" @selected($classwork->topic_id == $topic->id)>
                        {{ $topic->name }}
                    </option>
                @empty
                    <option value="">No topics available</option>
                @endforelse
            </select>
        </x-form.floating-control>
    </div>
</div>
<button type="submit" class="btn btn-primary">{{ $button_label }}</button>

@push('scripts')
    <script src="https://cdn.tiny.cloud/1/epbdgqcp375sf2k973wba6o0uz5amllbkqt5xjc1f8mlmp2q/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#description',
            plugins: [
                // Core editing features
                'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media',
                'searchreplace', 'table', 'visualblocks', 'wordcount',
                // Your account includes a free trial of TinyMCE premium features
                // Try the most popular premium features until Jul 20, 2025:
                'checklist', 'mediaembed', 'casechange', 'formatpainter', 'pageembed', 'a11ychecker',
                'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage',
                'advtemplate', 'ai', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags',
                'autocorrect', 'typography', 'inlinecss', 'markdown', 'importword', 'exportword', 'exportpdf'
            ],
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        });
    </script>
@endpush
