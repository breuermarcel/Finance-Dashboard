@extends('layouts.app')

@section('content')
    <main class="form-signin">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <img src="{{ asset('images/Logo_Trad.png') }}" title="Consulting-Breuer" alt="Logo" class="mb-3"/>

            <label for="email" class="visually-hidden">{{ trans('E-Mail Address') }}</label>
            <input type="email" id="email" name="email" class="form-control"
                   autocomplete="email" placeholder="{{ trans('E-Mail Address') }}" required autofocus>

            <label for="password" class="visually-hidden">{{ trans('Password') }}</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="{{ trans('Password') }}"
                   autocomplete="current-password" required>

            <button class="w-100 btn btn-lg btn-outline-secondary mt-2" type="submit">{{ trans('Login') }}</button>
        </form>
    </main>
@endsection
