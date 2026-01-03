@extends('layouts.master')
@section('title', 'People ')
@section('content')
    <div class="div container">
        <h1>{{ $classroom->name }} People</h1>
        <x-alert name="success" class="alert-success" />
        <x-alert name="error" class="alert-danger" />
        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Role</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                {{-- @dd($classroom->name) --}}
                @forelse ($classroom->users()->orderBy('name')->get() as $user)
                    <tr>
                        <td></td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->pivot->role }}</td>
                        <td>
                            <form action="{{ route('classrooms.people.destroy', $classroom->id) }}" method="POST">
                                @csrf
                                @method('delete')
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No users in this classroom</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
