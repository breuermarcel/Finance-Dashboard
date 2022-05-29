<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\Stock;

class SearchController extends Controller
{
    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return View::make('components.search-results')->with('searchResults', $this->doSearch($request->query('sword')));
        }
    }

    /**
     * @param string $val
     */
    public function doSearch(string $val)
    {
        return Stock::select('symbol', 'name')
            ->where('symbol', 'LIKE', '%' . $val . '%')
            ->orWhere('name', 'LIKE', '%' . $val . '%')
            ->get();
    }
}
