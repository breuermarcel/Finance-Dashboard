@extends('layouts.app')

@section('content')
    @if ($logs->count() > 0)

        <div class="btn-toolbar mb-2">
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-secondary">
                    <span class="px-1 badge bg-danger">{{ $logs->count() }}</span> Errors
                </button>

                <form class="d-inline" method="POST" action="{{ route('maintenance.logs.truncate') }}">
                    @csrf

                    <button class="btn btn-sm btn-outline-danger" type="submit">{{ trans('Delete All') }}</button>
                </form>
            </div>
        </div>

        <ul class="list-group">
            @forelse($logs as $log)
                <li class="list-group-item">
                    <form id="log-{{ $loop->index }}" class="d-inline" method="POST"
                          action="{{ route('maintenance.logs.destroy', $log) }}">
                        @csrf
                        @method('DELETE')
                        <input class="form-check-input me-1" type="radio"
                               onclick="event.preventDefault();document.getElementById('logs-{{ $loop->index }}').submit();">
                        <span>{!! $log->message !!}</span>
                    </form>
                </li>
            @empty
            @endforelse
        </ul>
    @else
        No errors occurred.
    @endif

@endsection
