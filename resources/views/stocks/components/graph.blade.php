<div class="col-sm-12 col-lg-6">
    <div class="card rounded-0 shadow-sm">
        <div class="card-header fw-bolder justify-content-between d-flex align-items-center">
            <span class="me-2">{{ trans('History') }}</span>

            <div class="btn-group btn-group-sm" role="group" aria-label="{{ trans('Period') }}">
                <a class="btn btn-outline-danger" href="{{ route('stocks.show', $stock->symbol) }}?period=30">1 {{ trans('Month') }}</a>
                <a class="btn btn-outline-danger" href="{{ route('stocks.show', $stock->symbol) }}?period=180">6 {{ trans('Months') }}</a>
                <a class="btn btn-outline-danger" href="{{ route('stocks.show', $stock->symbol) }}?period=365">1 {{ trans('Year') }}</a>
                <a class="btn btn-outline-danger" href="{{ route('stocks.show', $stock->symbol) }}?period=1095">3 {{ trans('Years') }}</a>
                <a class="btn btn-outline-danger" href="{{ route('stocks.show', $stock->symbol) }}?period=1825">5 {{ trans('Years') }}</a>
            </div>
        </div>

        <div class="card-body">
            <canvas id="stock"></canvas>
            <script>
                var ctx = document.getElementById('stock').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: [
                            @foreach($history as $data)
                                '{{ $data['date'] }}',
                            @endforeach
                        ],
                        datasets: [{
                            label: '{{ $stock->name }} ({{ $stock->symbol }})',
                            fill: false,
                            borderColor: 'rgb(255, 99, 132)',
                            pointRadius: 0,
                            lineTension: 0,
                            data: [
                                @foreach($history as $data)
                                    '{{ $data['adj_close'] }}',
                                @endforeach
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: '{{ $stock->name }} ({{ $stock->symbol }})'
                        },
                        scales: {
                            xAxes: [{
                                display: true,
                            }],
                            yAxes: [{
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Value in {{ $information['finance']['Financial Currency'] ?? '-' }}'
                                }
                            }]
                        },
                    }
                });
            </script>
        </div>
    </div>
</div>
