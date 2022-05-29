<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockInformation;
use App\Models\User;
use App\Services\APIService;

class StockAnalysisController extends Controller
{
    /**
     * @param User $user
     */
    public function index(User $user)
    {
        $preferences = $user->find(auth()->id())->stockAnalysisPreferences()->orderBy('indicator', 'ASC')->get();

        if ($preferences->count() > 0) {
            $data = [];
            $analysis = $this->getStocksOnPreference($preferences);

            if ($analysis->count() > 0) {
                $analysis = $analysis->toArray();
                $preferences = $preferences->toArray();

                foreach ($analysis as $stock) {
                    foreach ($preferences as $p_key => $preference) {
                        $data[$stock['stock']['name']]['symbol'] = $stock['stock']['symbol'];
                        $data[$stock['stock']['name']]['analysis'][$p_key][$preference['indicator']] = [
                            'search' => [
                                'indicator' => str_replace('_', ' ', $preference['indicator']),
                                'expression' => $preference['expression'],
                                'value' => $preference['value']
                            ],
                            'results' => $stock[$preference['indicator']]
                        ];
                    }
                }

                return view('analysis.index', [
                    'stocks' => $data
                ]);
            } else {
                return redirect()->route('stocks.analysis.settings.index')->with('error', 'No stocks found, on your criteria.');
            }
        }

        return redirect()->route('stocks.analysis.settings.create')->with('error', 'Please create at least one criteria.');
    }

    /**
     * @param $settings
     */
    public function getStocksOnPreference($settings)
    {
        $max_items = $settings->count();
        $_query = '';

        if ($max_items <= 1) {
            $_query .= $settings->first()->indicator . $settings->first()->expression . $settings->first()->value;
        } else {
            for ($i = 0; $i < $max_items; $i++) {
                if ($i === 0) {
                    $_query .= $settings[$i]->indicator . $settings[$i]->expression . $settings[$i]->value . ' ';
                } else {
                    $_query .= ' AND ' . $settings[$i]->indicator . $settings[$i]->expression . $settings[$i]->value;
                }
            }
        }

        return StockInformation::whereRaw($_query)->with('stock')->get();
    }

    /**
     * @param string $symbol
     */
    public function store(Stock $stock)
    {
        $statistic = APIService::getStatistic($stock->symbol, true);

        if (count($statistic) > 0) {
            $stock->stockInformation()->save(new StockInformation($statistic));
        }
    }

    public function create()
    {
        foreach (StockInformation::all() as $analysis) {
            $analysis->delete();
        }

        $stocks = Stock::all();
        $count_items = $stocks->count();

        $data = [];
        $i = 0;
        foreach ($stocks as $stock) {
            $data[$i]['symbol'] = $stock->symbol;
            $data[$i]['url'] = route('stocks.analysis.call-api.store', $stock->symbol);
            $i++;
        }

        return view('analysis.create', [
            'stocks' => $data,
            'count_items' => $count_items
        ]);
    }
}
