<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockImportController extends Controller
{
    public function index()
    {
        return view('stocks.import');
    }

    public function importStocks(Request $request)
    {
        $file = $request->csvFile;
        $filename = date('U') . $file->getClientOriginalName();

        // File upload location
        $location = 'uploads';

        // Upload file
        $file->move($location, $filename);

        // Import CSV to Database
        $filepath = public_path($location . "/" . $filename);

        // Reading file
        $file = fopen($filepath, "r");

        $importData_arr = [];

        while (($filedata = fgetcsv($file)) !== FALSE) {
            $importData_arr[] = explode(';', $filedata[0]);
        }

        fclose($file);

        foreach ($importData_arr as $importData) {
            $symbol = strtoupper($importData[0]);

            // Check if Stock already exists
            if (Stock::where('symbol', $symbol)->count() === 0) {
                Stock::create([
                    'symbol' => $symbol,
                    'name' => $importData[1]
                ]);
            }
        }

        return redirect()->route('stocks.import.index')->with('success', 'Successfully imported.');
    }
}
