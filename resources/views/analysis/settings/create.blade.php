@extends('layouts.app')

@section('content')
    <form class="row g-4 mw-50" method="POST" action="{{ route('stocks.analysis.settings.store') }}">
        @csrf
        <div class="col-4 form-floating">
            <select id="indicator" name="indicator" class="form-control">
                <option value="" disabled selected>{{ trans('Please Select') }}</option>
                @foreach ($indicators as $indicator)
                    @if ($indicator !== 'id' && $indicator !== 'symbol' && $indicator !== 'stock_id')
                        <option value="{{ $indicator }}">{{ str_replace('_', ' ', $indicator) }}</option>
                    @endif
                @endforeach
            </select>
            <label for="indicator" class="ps-4">{{ trans('Indicator') }}</label>
        </div>
        <div class="col-4 form-floating">
            <select id="expression" name="expression" class="form-control">
                <option value="" disabled selected>{{ trans('Please Select') }}</option>
                @foreach ($expressions as $expression)
                    <option value="{{ $expression }}" />{{ $expression }}</option>
                @endforeach
            </select>
            <label for="expression" class="ps-4">{{ trans('Expression') }}</label>
        </div>
        <div class="col-4 form-floating">
            <input name="value" type="text" class="form-control" id="value" required>
            <label for="value" class="ps-4">{{ trans('Value') }}</label>
        </div>

        <div class="col-12">
            <button class="btn btn-outline-secondary">{{ trans('Create') }}</button>
        </div>
    </form>
@endsection
