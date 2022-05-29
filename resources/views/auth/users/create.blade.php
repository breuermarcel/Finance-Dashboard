@extends('layouts.app')

@section('content')
    <form class="row g-4 mw-75" method="POST" action="{{ route('users.store') }}">
        @csrf

        <div class="col-md-6 form-floating">
            <input name="firstname" type="text" class="form-control" id="firstname"
                   placeholder="{{ trans('First Name') }}" value="{{ old('firstname') }}" required autofocus>
            <label for="firstname" class="ps-4">{{ trans('First Name') }}</label>
        </div>
        <div class="col-md-6 form-floating">
            <input name="lastname" type="text" class="form-control" id="lastname"
                   placeholder="{{ trans('Last Name') }}" value="{{ old('lastname') }}" required>
            <label for="lastname" class="ps-4">{{ trans('Last Name') }}</label>
        </div>

        <div class="col-12 form-floating">
            <input name="email" type="email" class="form-control" id="email" placeholder="{{ trans('E-Mail Address') }}"
                   value="{{ old('email') }}"
                   required>
            <label for="email" class="ps-4">{{ trans('E-Mail Address') }}</label>
        </div>

        <div class="col-md-6 form-floating">
            <input type="text" id="password" name="password" class="form-control"
                   placeholder="{{ trans('Password') }}" autocomplete="new-password">
            <label for="password" class="ps-4">{{ trans('Password') }}</label>
        </div>

        <div class="col-md-6 form-floating">
            <input type="text" id="password-confirm" name="password_confirmation" class="form-control"
                   placeholder="{{ trans('Confirm Password') }}" autocomplete="new-password">
            <label for="password-confirm" class="ps-4">{{ trans('Confirm Password') }}</label>
        </div>

        <div class="col-12">
            <button class="btn btn-outline-secondary">{{ trans('Create') }}</button>
        </div>
    </form>
@endsection
