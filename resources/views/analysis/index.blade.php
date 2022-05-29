@extends('layouts.app')

@section('content')
    <script src="{{ asset('js/mansory.min.js') }}" async></script>

    <div class="row g-4" data-masonry='{"percentPosition": true }'>
        @foreach($stocks as $stock => $data)
            <div class="col-sm-6 col-lg-4">
                <div class="card rounded-0 shadow-sm">
                    <div class="card-header fw-bolder">
                        <a href="{{ route('stocks.show', $data['symbol']) }}" title="{{ $data['symbol'] }}" target="_self"
                           class="stretched-link">{{ $stock }}</a>
                    </div>
                    <div class="card-body table-responsive py-0 px-2">
                        <table class="table table-borderless my-0">
                            <thead class="d-none">
                            <tr>
                                <th scope="col">{{ trans('Criteria') }}</th>
                                <th scope="col">{{ trans('Result') }}</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach ($data['analysis'] as $searchResult)
                                @foreach ($searchResult as $result)
                                    <tr>
                                        <td class="text-capitalize">{{ $result['search']['indicator'] }} {{ $result['search']['expression'] }} {{ $result['search']['value'] }}</td>
                                        <td class="fw-bolder">{{ $result['results'] }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
