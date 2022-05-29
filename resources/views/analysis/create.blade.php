@extends('layouts.app')

@section('content')
    <div class="progress mb-3">
        <div class="progress-bar bg-info" id="progressbar" role="progressbar" style="width: 0%;" aria-valuenow="25"
             aria-valuemin="0" aria-valuemax="100">0%
        </div>
    </div>

    <div class="status-container"></div>

    <script>
        let percentageContainer = $('#progressbar');
        let spinner = $('.spinner-border');
        let i = 0;
        let howManyTimes = {{ $count_items }};
        let stocks = @json($stocks);

        let contentContainer = $('.status-container');

        function doGenerate() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.post({
                type: 'POST',
                url: stocks[i]['url'],
                data: stocks[i]['symbol'],
                success: function () {
                    i++;

                    let percentage = i / howManyTimes * 100;
                    let success_factor = parseFloat(percentage).toFixed(2);

                    percentageContainer.css('width', success_factor + '%');
                    percentageContainer.attr('aria-valuenow', success_factor)
                    percentageContainer.text(success_factor + '%');

                    if (i < howManyTimes) {
                        doGenerate();
                    }
                },
                error: function () {
                    contentContainer.prepend(
                        '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' + stocks[i]["symbol"] + '</strong> an error occurred.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                    );

                    i++;

                    let percentage = (i) / howManyTimes * 100;
                    let success_factor = parseFloat(percentage).toFixed(2);

                    percentageContainer.css('width', success_factor + '%');
                    percentageContainer.attr('aria-valuenow', success_factor)
                    percentageContainer.text(success_factor + '%');

                    if (i < howManyTimes) {
                        doGenerate();
                    }
                }
            });
        }

        doGenerate();
    </script>
@endsection
