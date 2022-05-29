<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\APIService;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::paginate(10);

        return view('stocks.list', compact('stocks'));
    }

    public function create()
    {
        return view('stocks.create');
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        $request = $request->only(['symbol', 'wkn', 'isin', 'name']);

        $request['symbol'] = Str::upper($request['symbol']);
        $request['wkn'] = Str::upper($request['wkn']);
        $request['isin'] = Str::upper($request['isin']);

        $validator = Validator::make($request, [
            'symbol' => ['required', 'string', 'max:255', 'unique:stocks'],
            'wkn' => ['string', 'max:255'],
            'isin' => ['string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Stock::create($request);
        APIService::getStatistic($request['symbol'], true);

        return redirect()->route('stocks.index')->with('success', 'Stock successfully added.');

    }

    /**
     * @param Stock $stock
     * @param Request $request
     */
    public function show(Stock $stock, Request $request)
    {
        if ($request->has('period')) {
            $validator = Validator::make($request->only('period'), [
                'period' => ['required', 'integer'],
            ]);

            if ($validator->fails()) {
                return redirect()->route('stocks.show', $stock)->with('error', 'Please enter a valid period.');
            } else {
                $period = $request->period;
            }
        } else {
            $period = 90;
        }

        return view('stocks.show', [
            'stock' => $stock,
            'history' => APIService::stockHistory($stock->symbol, $period),
            'information' => APIService::getStatistic($stock->symbol, false)
        ]);
    }

    /**
     * @param Stock $stock
     */
    public function edit(Stock $stock)
    {
        return view('stocks.edit', compact('stock'));
    }

    /**
     * @param Request $request
     * @param Stock $stock
     */
    public function update(Request $request, Stock $stock)
    {
        $request = $request->only(['symbol', 'wkn', 'isin', 'name']);

        $request['symbol'] = Str::upper($request['symbol']);
        $request['wkn'] = Str::upper($request['wkn']);
        $request['isin'] = Str::upper($request['isin']);

        if ($request['symbol'] !== $stock->symbol) {
            $validator = Validator::make($request, [
                'symbol' => ['required', 'string', 'max:255', 'unique:stocks'],
                'wkn' => ['string', 'max:255'],
                'isin' => ['string', 'max:255'],
                'name' => ['required', 'string', 'max:255'],
            ]);
        } else {
            $validator = Validator::make($request, [
                'symbol' => ['required', 'string', 'max:255'],
                'wkn' => ['string', 'max:255'],
                'isin' => ['string', 'max:255'],
                'name' => ['required', 'string', 'max:255'],
            ]);
        }

        if ($validator->fails()) {
            return redirect()->route('stocks.index')->withErrors($validator)->withInput();
        }

        $stock->update($request);
        return redirect()->route('stocks.index')->with('success', 'Stock updated.');
    }

    /**
     * @param Stock $stock
     */
    public function destroy(Stock $stock)
    {
        $stock->delete();

        return redirect()->route('stocks.index')->with('success', 'Stock deleted.');
    }
}
