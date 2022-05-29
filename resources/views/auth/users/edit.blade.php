@extends('layouts.app')

@section('content')
    <form class="row g-4 mw-75" method="POST" action="{{ route('users.update', $user) }}">
        @csrf
        @method('PUT')

        <div class="col-md-6 form-floating">
            <input value="{{ $user->firstname }}" name="firstname" type="text" class="form-control" id="firstname"
                   placeholder="{{ trans('First Name') }}" required>
            <label for="firstname" class="ps-4">{{ trans('First Name') }}</label>
        </div>
        <div class="col-md-6 form-floating">
            <input value="{{ $user->lastname }}" name="lastname" type="text" class="form-control" id="lastname"
                   placeholder="{{ trans('Last Name') }}" required>
            <label for="lastname" class="ps-4">{{ trans('Last Name') }}</label>
        </div>

        <div class="col-12 form-floating">
            <input value="{{ $user->email }}" name="email" type="email" class="form-control" id="email"
                   placeholder="{{ trans('E-Mail Address') }}" required>
            <label for="email" class="ps-4">{{ trans('E-Mail Address') }}</label>
        </div>

        <div class="col-md-6 form-floating">
            <input type="text" id="password" name="password" class="form-control" placeholder="{{ trans('Password') }}">
            <label for="password" class="ps-4">{{ trans('Password') }}</label>
        </div>

        <div class="col-md-6 form-floating">
            <input type="text" id="password-confirm" name="password_confirmation" class="form-control"
                   placeholder="{{ trans('Confirm Password') }}">
            <label for="password-confirm" class="ps-4">{{ trans('Confirm Password') }}</label>
        </div>

        <div class="col-12">
            <button class="btn btn-outline-secondary">{{ trans('Update') }}</button>
        </div>
    </form>
@endsection
