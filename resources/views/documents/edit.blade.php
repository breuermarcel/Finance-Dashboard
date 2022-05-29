@extends('layouts.app')

@section('content')
    <script src="//cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>

    <form class="row g-3 mw-75" method="POST" action="{{ route('wiki.update', $wiki) }}">
        @csrf
        @method('PUT')

        <div class="col-md-7 form-floating">
            <input name="title" type="text" class="form-control" id="title" value="{{ $wiki->title }}" required>
            <label for="title" class="ps-4">{{ trans('Title') }}</label>
        </div>
        <div class="col-md-5 form-floating">
            <input name="author" type="text" class="form-control" id="author" value="{{ $wiki->author }}" required>
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
                <input class="form-check-input" name="show" type="checkbox"
                       id="list-article" {{ $wiki->show ? 'checked' : '' }}>
                <label class="form-check-label" for="list-article">
                    {{ trans('Published') }}
                </label>
            </div>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-outline-secondary">{{ trans('Update') }}</button>
        </div>
    </form>

    <script>
        $(function () {
            CKEDITOR.replace('teaser');
            CKEDITOR.replace('body');

            $("#teaser").val({!! json_encode($wiki->teaser) !!});
            $("#body").val({!! json_encode($wiki->body) !!});
        });

    </script>
@endsection
