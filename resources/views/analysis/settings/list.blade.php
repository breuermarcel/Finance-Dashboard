@extends('layouts.app')

@section('content')
    <div class="btn-group">
        <a href="{{ route('stocks.analysis.settings.create') }}" class="btn btn-sm btn-outline-secondary">{{ trans('Create') }}</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>{{ trans('Indicator') }}</th>
                <th>{{ trans('Expression') }}</th>
                <th>{{ trans('Value') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($settings as $setting)
                <tr>
                    <td>
                        {{ str_replace('_', ' ', $setting->indicator) }}
                    </td>
                    <td>{{ $setting->expression }}</td>
                    <td>{{ $setting->value }}</td>
                    <td class="text-end">
                        <form class="d-inline" method="POST"
                              action="{{ route('stocks.analysis.settings.destroy', $setting) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">{{ trans('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
