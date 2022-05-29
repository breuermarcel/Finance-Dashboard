@extends('layouts.app')

@section('content')
    <main>
        @if(auth()->user()->isAdmin())
            <div class="btn-toolbar mb-2">
                <div class="btn-group">
                    <form method="POST" action="{{ route('stocks.destroy', $stock) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">{{ trans('Delete') }}</button>
                    </form>
                </div>
            </div>
        @endif

        <script src="{{ asset('js/mansory.min.js') }}" async></script>
        <script src="{{ asset('js/chart.min.js') }}"></script>

        <div class="row g-4" data-masonry='{"percentPosition": true }'>
            @if (!empty($history))
                @include('stocks.components.graph')
            @endif

            @if (!empty($information))
                @include('stocks.components.informations')
            @endif
        </div>
    </main>
@endsection
