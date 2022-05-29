<div class="col-sm-12 col-lg-6">
    <div class="card rounded-0 shadow-sm">
        <div class="card-header fw-bolder">
            {{ trans('Profile') }}
        </div>

        <div class="card-body">
            <p><strong>{{ $stock->name }}</strong></p>

            @if ($information['Address']!== '-' && $information['ZIP']!== '-' && $information['City']!== '-' && $information['Country']!== '-' && $information['Website'])
                <div class="row">
                    <div class="col-xl-6">
                        <p>
                            {{ $information['Address'] }}<br/>
                            {{ $information['ZIP'] }}
                            {{ $information['City'] }}<br/>
                            {{ $information['Country'] }}<br/>
                            <a href="{{ $information['Website'] }}" target="_blank"
                               title="{{ $stock->name }}">{{ $information['Website'] }}</a>
                        </p>
                    </div>

                    <div class="col-xl-6">
                        <p>
                            {{ trans('Sector') }}: {{ $information['Sector'] }}<br/>
                            {{ trans('Industry') }}: {{ $information['Industry'] }}<br/>
                            {{ trans('Full Time Employees') }}: {{ $information['Full_Time_Employees'] }}<br/>
                            {{ trans('Current Stock Price') }}
                            : {{ $information['Current_Price'] }} {{ $information['Currency_Symbol'] }}<br/>

                            {{ trans('Recommendation') }}:
                            @if($information['Recommendation'] === 'buy' || $information['Recommendation'] === 'strong_buy')
                                <span
                                        class="badge rounded-pill bg-success text-capitalize">{{ trans('Buy') }}</span>
                            @elseif ($information['Recommendation'] === 'hold')
                                <span
                                        class="badge rounded-pill bg-warning text-dark text-capitalize">{{ $information['Recommendation'] }}</span>
                            @else
                                <span
                                        class="badge rounded-pill bg-danger text-capitalize">{{ $information['Recommendation'] }}</span>
                            @endif
                        </p>
                    </div>

                    <div class="col-12">
                        <p>{{ $information['Summary'] }}</p>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-12">
                        <p>
                            {{ trans('Sector') }}: {{ $information['Sector'] }}<br/>
                            {{ trans('Industry') }}: {{ $information['Industry'] }}<br/>
                            {{ trans('Full Time Employees') }}: {{ $information['Full_Time_Employees'] }}<br/>
                            {{ trans('Current Stock Price') }}: {{ $information['Current_Price'] }}<br/>
                            {{ trans('Recommendation') }}:
                            @if($information['Recommendation'] === 'buy' || $information['Recommendation'] === 'strong_buy')
                                <span
                                        class="badge rounded-pill bg-success text-capitalize">{{ $information['Recommendation'] }}</span>
                            @elseif ($information['Recommendation'] === 'hold')
                                <span
                                        class="badge rounded-pill bg-warning text-dark text-capitalize">{{ $information['Recommendation'] }}</span>
                            @else
                                <span
                                        class="badge rounded-pill bg-danger text-capitalize">{{ $information['Recommendation'] }}</span>
                            @endif
                        </p>
                    </div>

                    @if ($information['Summary'] !== '-')
                        <div class="col-12">
                            <p>{{ $information['Summary'] }}</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<div class="col-sm-12 col-lg-6 ">
    <div class="card rounded-0 shadow-sm">
        <div class="card-header fw-bolder">
            {{ trans('Evaluation Criteria') }}
        </div>

        <div class="card-body table-responsive">
            <table class="table table-borderless">
                <thead class="d-none">
                <tr>
                    <th scope="col">{{ trans('Description') }}</th>
                    <th scope="col">{{ trans('Value') }}</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">{{ trans('Market Capitalization') }}</th>
                    <td>{{ $information['Market_Capitalization'] }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ trans('Enterprise Value') }}</th>
                    <td>{{ $information['Enterprise_Value'] }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ trans('Forward P/E') }}</th>
                    <td>{{ $information['Forward_PE'] }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ trans('PEG Ratio') }}</th>
                    <td>{{ $information['PEG_Ratio'] }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ trans('Price/Book') }}</th>
                    <td>{{ $information['Price_To_Book'] }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ trans('Enterprise Value/Revenue') }}</th>
                    <td>{{ $information['Enterprise_To_Revenue'] }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ trans('Enterprise Value/EBITDA') }}</th>
                    <td>{{ $information['Enterprise_To_EBITDA'] }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="col-sm-12 col-lg-6 ">
    <div class="card rounded-0 shadow-sm">
        <div class="card-header fw-bolder">
            {{ trans('Recommendation Trend') }}
        </div>

        <div class="card-body">
            <canvas id="recommendation"></canvas>
            <script>
                var ctx = document.getElementById('recommendation').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [
                            '{{ $information['recommendationTrend'][3]['Date'] }}',
                            '{{ $information['recommendationTrend'][2]['Date'] }}',
                            '{{ $information['recommendationTrend'][1]['Date'] }}',
                            '{{ $information['recommendationTrend'][0]['Date'] }}',
                        ],
                        datasets: [
                            {
                                label: 'Strong Sell',
                                data: [
                                    {{ $information['recommendationTrend'][3]['Strong_Sell'] }},
                                    {{ $information['recommendationTrend'][2]['Strong_Sell'] }},
                                    {{ $information['recommendationTrend'][1]['Strong_Sell'] }},
                                    {{ $information['recommendationTrend'][0]['Strong_Sell'] }}
                                ],
                                backgroundColor: 'rgb(255, 51, 58)',
                            },
                            {
                                label: 'Sell',
                                data: [
                                    {{ $information['recommendationTrend'][3]['Sell'] }},
                                    {{ $information['recommendationTrend'][2]['Sell'] }},
                                    {{ $information['recommendationTrend'][1]['Sell'] }},
                                    {{ $information['recommendationTrend'][0]['Sell'] }}
                                ],
                                backgroundColor: 'rgb(255, 163, 62)',
                            },
                            {
                                label: 'Hold',
                                data: [
                                    {{ $information['recommendationTrend'][3]['Hold'] }},
                                    {{ $information['recommendationTrend'][2]['Hold'] }},
                                    {{ $information['recommendationTrend'][1]['Hold'] }},
                                    {{ $information['recommendationTrend'][0]['Hold'] }}
                                ],
                                backgroundColor: 'rgb(255, 220, 72)',
                            },
                            {
                                label: 'Buy',
                                data: [
                                    {{ $information['recommendationTrend'][3]['Buy'] }},
                                    {{ $information['recommendationTrend'][2]['Buy'] }},
                                    {{ $information['recommendationTrend'][1]['Buy'] }},
                                    {{ $information['recommendationTrend'][0]['Buy'] }}
                                ],
                                backgroundColor: 'rgb(0, 192, 115)',
                            },
                            {
                                label: 'Strong Buy',
                                data: [
                                    {{ $information['recommendationTrend'][3]['Strong_Buy'] }},
                                    {{ $information['recommendationTrend'][2]['Strong_Buy'] }},
                                    {{ $information['recommendationTrend'][1]['Strong_Buy'] }},
                                    {{ $information['recommendationTrend'][0]['Strong_Buy'] }}
                                ],
                                backgroundColor: 'rgb(0, 143, 136)',
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        legend: {
                            display: false
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: '{{ trans('Recommendation Trends for') }} {{ $stock->symbol }}'
                            },
                        },
                        scales: {
                            x: {
                                stacked: true,
                            },
                            y: {
                                stacked: true
                            },
                        },
                    }
                });
            </script>
        </div>
    </div>
</div>

<div class="col-sm-12 col-lg-6 ">
    <div class="card rounded-0 shadow-sm">
        <div class="card-header fw-bolder">
            {{ trans('Profitability') }}
        </div>

        <div class="card-body table-responsive">
            <table class="table table-borderless">
                <thead class="d-none">
                <tr>
                    <th scope="col">{{ trans('Description') }}</th>
                    <th scope="col">{{ trans('Value') }}</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">{{ trans('Profit Margin') }}</th>
                    <td>{{ $information['Profit_Margins'] }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ trans('Operating Margin') }}</th>
                    <td>{{ $information['Operating_Margins'] }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ trans('Gross Margin') }}</th>
                    <td>{{ $information['Gross_Margins'] }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ trans('EBITDA Margin') }}</th>
                    <td>{{ $information['EBITDA_Margins'] }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ trans('Return on Investment') }}</th>
                    <td>{{ $information['EBITDA_Margins'] }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="col-sm-12 col-lg-6 ">
    <div class="card rounded-0 shadow-sm">
        <div class="card-header fw-bolder">
            {{ trans('Profit and Loss') }}
        </div>

        <div class="card-body table-responsive">
            <table class="table table-borderless">
                <thead class="d-none">
                <tr>
                    <th scope="col">{{ trans('Description') }}</th>
                    <th scope="col">{{ trans('Value') }}</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">{{ trans('Turnover') }}</th>
                    <td>{{ $information['Total_Revenue'] }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ trans('Turnover per Share') }}</th>
                    <td>{{ $information['Debt_To_Equity'] }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ trans('EBITDA') }}</th>
                    <td>{{ $information['EBITDA'] }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ trans('EPS') }}</th>
                    <td>{{ $information['Trailing_EPS'] }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="col-sm-12 col-lg-6 ">
    <div class="card rounded-0 shadow-sm">
        <div class="card-header fw-bolder">
            {{ trans('Balance Sheet') }}
        </div>

        <div class="card-body table-responsive">
            <table class="table table-borderless">
                <thead class="d-none">
                <tr>
                    <th scope="col">{{ trans('Description') }}</th>
                    <th scope="col">{{ trans('Value') }}</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">{{ trans('Cash') }}</th>
                    <td>{{ $information['Total_Cash'] }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ trans('Cash per Share') }}</th>
                    <td>{{ $information['Total_Cash_Per_Share'] }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ trans('Current Ratio') }}</th>
                    <td>{{ $information['Current_Ratio'] }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="col-sm-12 col-lg-6 ">
    <div class="card rounded-0 shadow-sm">
        <div class="card-header fw-bolder">
            {{ trans('Shorts') }}
        </div>

        <div class="card-body table-responsive">
            <table class="table table-borderless">
                <thead class="d-none">
                <tr>
                    <th scope="col">{{ trans('Description') }}</th>
                    <th scope="col">{{ trans('Value') }}</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">{{ trans('Shares (Short)') }}</th>
                    <td>{{ $information['Shares_Short'] }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ trans('Short Ratio') }}</th>
                    <td>{{ $information['Short_Ratio'] }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="col-sm-12 col-lg-6 ">
    <div class="card rounded-0 shadow-sm">
        <div class="card-header fw-bolder">
            {{ trans('Dividends and Splits') }}
        </div>

        <div class="card-body table-responsive">
            <table class="table table-borderless">
                <thead class="d-none">
                <tr>
                    <th scope="col">{{ trans('Description') }}</th>
                    <th scope="col">{{ trans('Value') }}</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">{{ trans('Comp. Annual Dividend Rate') }}</th>
                    <td>{{ $information['Last_Dividend'] }} {{ $information['Currency_Symbol'] }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ trans('Dividend Date') }}</th>
                    <td>{{ $information['Last_Dividend_Date'] }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ trans('Last Split Factor') }}</th>
                    <td>{{ $information['Last_Split_Factor'] }}</td>
                </tr>
                <tr>
                    <th scope="row">{{ trans('Last Split Date ') }}</th>
                    <td>{{ $information['Last_Split_Date'] }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
