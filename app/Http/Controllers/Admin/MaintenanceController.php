<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Artisan;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function enableCache()
    {
        Artisan::call('route:cache');

        return redirect()->route('stocks.index')->with('success', 'Cache enabled.');
    }

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('queue:restart');

        return redirect()->route('stocks.index')->with('success', 'Cache cleared.');
    }

    public function resetMigration() {
        Artisan::call('migrate:reset');

        return redirect()->route('stocks.index')->with('success', 'Migration reseted.');
    }

    public function freshMigration(Request $request)
    {
        Artisan::call('migrate:fresh');

        if ($request->has('seed'))
            Artisan::call('db:seed');

        return redirect()->route('stocks.index')->with('success', 'Migrated.');
    }
}
