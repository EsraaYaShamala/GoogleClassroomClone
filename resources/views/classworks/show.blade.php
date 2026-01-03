@extends('layouts.master')
@section('title', 'Show Classwork')
@section('content')
    <div class="container">
        <h1>{{ $classroom->name }}(#{{ $classroom->id }})</h1>
        <h3>{{ $classwork->title }}</h3>
        <x-alert name="success" class="alert-success" />
        <x-alert name="error" class="alert-danger" />
        <div class="row">
            <div class="col-md-8">
                <div class="div">
                    <p>{!! $classwork->description !!}</p>
                </div>
                <hr>
                <h4>Comments</h4>
                <form action="{{ route('comments.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $classwork->id }}">
                    <input type="hidden" name="type" value="classwork">
                    <div class="d-flex">
                        <div class="col-8">
                            <x-form.floating-control name="description" placeholder="Comment">
                                <x-form.input-text-area name="content" placeholder="Comment" />
                            </x-form.floating-control>
                        </div>
                        <div class="ms-1">
                            <button type="submit" class="btn btn-primary">Create Comment</button>
                        </div>
                    </div>
                </form>
                <div class="mt-4">
                    @foreach ($classwork->comments as $comment)
                        <div class="row">
                            <div class="col-md-2">
                                {{-- <img src="{{ asset('storage/' . $comment->user->avatar) }}" alt="Avatar" class="rounded-circle"> --}}
                            </div>
                            <div class="col-md-10">
                                <p>{{ $comment->content }}</p>
                                <p>Posted by {{ $comment->user->name }}</p>
                                <p>Posted at {{ $comment->created_at->diffForHumans(null, true, true) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-3 bg-light">
                    <h4>Submissions</h4>
                    @if ($submissions->count())
                        <ul>
                            @foreach ($submissions as $submission)
                                @can('view', $submission)
                                    <li><a
                                            href="{{ route('submissions.file', $submission->id) }}">File#{{ $loop->iteration }}</a>
                                    </li>
                                @endcan
                            @endforeach
                        </ul>
                    @else
                        @can('submissions.create', [$classwork])
                            <form action="{{ route('submissions.store', $classwork->id) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <x-form.floating-control name="files" placeholder="Upload Files">
                                    <x-form.input type="file" accept="image/*,application/pdf" name="files[]" id="files"
                                        multiple />
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </x-form.floating-control>
                            </form>
                        @endcan
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
