@extends('layouts.app')

@section('content')
    @if (auth()->user()->isAdmin())
        <div class="btn-toolbar mb-2">
            <div class="btn-group me-1">
                <a href="{{ route('wiki.create') }}"
                   class="btn btn-sm btn-outline-secondary">{{ trans('Create') }}</a>
            </div>
        </div>
    @endif

    <script src="{{ asset('js/mansory.min.js') }}" async></script>

    <div class="row g-4" data-masonry='{"percentPosition": true }'>
        @foreach($wikis as $wiki)
            <div class="col-sm-6 col-lg-4">
                <div class="card p-4 rounded-0 shadow-sm">
                    <figure class="mb-0">
                        <h4 class="card-title">
                            <a href="{{ route('wiki.show', $wiki) }}" class="stretched-link"
                               title="{{ $wiki->title }}">{{ $wiki->title }}</a>
                        </h4>
                        <blockquote>
                            {!! $wiki->teaser !!}
                        </blockquote>
                        <figcaption class="blockquote-footer mb-0 text-muted">
                            {{ $wiki->author }}
                        </figcaption>
                    </figure>
                </div>
            </div>
        @endforeach
    </div>
@endsection
