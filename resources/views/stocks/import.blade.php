@extends('layouts.app')

@section('content')

    <form class="row g-4 mw-50" method="POST" action="{{ route('stocks.import.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="col-12">
            <label class="form-label" for="csvFile">{{ trans('Select File') }}</label>
            <input type="file" class="form-control" name="csvFile" id="csvFile" />
        </div>

        <div class="col-12">
            <button class="btn btn-outline-secondary">{{ trans('Upload') }}</button>
        </div>
    </form>

@endsection