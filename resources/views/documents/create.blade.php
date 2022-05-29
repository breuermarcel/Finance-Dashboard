@extends('layouts.app')

@section('content')
    <script src="//cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>

    <form class="row g-3 mw-75" method="POST" action="{{ route('wiki.store') }}">
        @csrf
        <div class="col-md-7 form-floating">
            <input name="title" type="text" class="form-control" id="title" placeholder="{{ trans('Title') }}" value="{{ old('title') }}" required>
            <label for="title" class="ps-4">{{ trans('Title') }}</label>
        </div>
        <div class="col-md-5 form-floating">
            <input name="author" type="text" class="form-control" id="author" placeholder="{{ trans('Author') }}" value="{{ old('author') }}" required>
            <label for="author" class="ps-4">{{ trans('Author') }}</label>
        </div>

        <div class="col-12">
            <label class="form-label" for="teaser">Teaser</label>
            <textarea name="teaser" id="teaser" required></textarea>
        </div>

        <div class="col-12">
            <label class="form-label" for="body">Document</label>
            <textarea name="body" id="body" required></textarea>
        </div>

        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input" name="show" type="checkbox" value="" id="list-article">
                <label class="form-check-label" for="list-article">
                    {{ trans('Publish') }}
                </label>
            </div>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-outline-secondary">{{ trans('Create') }}</button>
        </div>
    </form>

    <script>
        CKEDITOR.replace('teaser');
        CKEDITOR.replace('body');
    </script>
@endsection
