@extends('layouts.app')

@section('content')
    <form class="row g-4" method="POST" action="{{ route('stocks.update', $stock) }}">
        @csrf
        @method('PUT')

        <div class="col-md-4 form-floating">
            <input value="{{ $stock->symbol }}" name="symbol" type="text" class="form-control" id="symbol">
            <label for="symbol" class="ps-4">{{ trans('Symbol') }}</label>
        </div>

        <div class="col-md-4 form-floating">
            <input value="{{ $stock->wkn }}" name="wkn" type="text" class="form-control" id="wkn">
            <label for="wkn" class="ps-4">{{ trans('WKN') }}</label>
        </div>

        <div class="col-md-4 form-floating">
            <input value="{{ $stock->isin }}" name="isin" type="text" class="form-control" id="isin">
            <label for="isin" class="ps-4">{{ trans('ISIN') }}</label>
        </div>

        <div class="col-md-12 form-floating">
            <input value="{{ $stock->name }}" name="name" type="text" class="form-control" id="name" required autofocus>
            <label for="name" class="ps-4">{{ trans('Name') }}</label>
        </div>

        <div class="col-12">
            <button class="btn btn-outline-secondary">{{ trans('Update') }}</button>
        </div>
    </form>
@endsection
