<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" translate="no">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex"/>
    <meta name="google" content="notranslate">

    <title>{{ config('app.name', 'Financial-AI') }}</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/Logo.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/Logo_Consulting_Breuer.png') }}" sizes="96x96">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/Logo_Consulting_Breuer.png') }}">

    @guest
        <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    @else
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @endguest

    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jQuery.min.js') }}"></script>
</head>
<body>
@guest
    @yield('content')
@else
    @include('components.navbar')

    <div class="container-fluid">
        <div class="row">
            @include('components.sidebar')
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 my-sm-3 my-2">
                @include('components.breadcrumb')
                <div id="searchResultsContainer"></div>
                @include('components.status')
                @yield('content')
            </main>
        </div>
    </div>
@endguest
</body>
</html>
