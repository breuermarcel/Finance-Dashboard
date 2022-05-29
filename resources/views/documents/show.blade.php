@extends('layouts.app')

@section('content')
    @if(auth()->user()->isAdmin())
        <div class="btn-toolbar mb-2">
            <div class="btn-group me-1">
                <a href="{{ route('wiki.edit', $wiki) }}"
                   class="btn btn-sm btn-outline-secondary">{{ trans('Edit') }}</a>
            </div>
            <div class="btn-group me-1">
                <form class="d-inline" method="POST" action="{{ route('wiki.destroy', $wiki) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-secondary">{{ trans('Delete') }}</button>
                </form>
            </div>
        </div>
    @endif

    <div class="card rounded-0 shadow-sm mt-3 p-3 p-md-4">
        <h1>{{ $wiki->title }}</h1>

        <blockquote class="blockquote">
            <p class="mb-0">{!! $wiki->teaser !!}</p>
            <footer class="blockquote-footer">{{ $wiki->author }}</footer>
        </blockquote>

        <div class="document-text">
            {!! $wiki->body !!}
        </div>
    </div>
@endsection
