<?php

use App\Http\{Controllers\Admin\LogController,
    Controllers\Admin\MaintenanceController,
    Controllers\Admin\StockImportController,
    Controllers\Admin\UserController,
    Controllers\Auth\ProfileController,
    Controllers\SearchController,
    Controllers\StockAnalysisController,
    Controllers\UserAnalysisSettingsController,
    Controllers\StockController,
    Controllers\WikiController,
};
use Illuminate\Support\Facades\Route;

Auth::routes([
    'register' => false,
    'reset' => false
]);


Route::group(['middleware' => 'auth'], function () {
    Route::get('search', [SearchController::class, 'index'])->name('search');

    Route::group(['prefix' => 'wiki', 'as' => 'wiki.'], function () {
        Route::group(['middleware' => 'admin'], function () {
            Route::get('/create', [WikiController::class, 'create'])->name('create');
            Route::post('/', [WikiController::class, 'store'])->name('store');
            Route::get('/{wiki:slug}/edit', [WikiController::class, 'edit'])->name('edit');
            Route::put('/{wiki}', [WikiController::class, 'update'])->name('update');
            Route::delete('/{wiki}', [WikiController::class, 'destroy'])->name('destroy');
        });
        Route::get('/', [WikiController::class, 'index'])->name('index');
        Route::get('/{wiki:slug}', [WikiController::class, 'show'])->name('show');
    });

    Route::group(['prefix' => 'stocks', 'as' => 'stocks.'], function () {
        Route::group(['prefix' => 'analysis', 'as' => 'analysis.'], function () {
            Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
                Route::get('/', [UserAnalysisSettingsController::class, 'index'])->name('index');
                Route::get('/create', [UserAnalysisSettingsController::class, 'create'])->name('create');
                Route::post('/', [UserAnalysisSettingsController::class, 'store'])->name('store');
                Route::delete('/{setting}', [UserAnalysisSettingsController::class, 'destroy'])->name('destroy');
            });

            Route::group(['prefix' => 'call-api', 'as' => 'call-api.', 'middleware' => 'admin'], function () {
                Route::get('/', [StockAnalysisController::class, 'create'])->name('create');
                Route::post('/{stock:symbol}', [StockAnalysisController::class, 'store'])->name('store');
            });

            Route::get('/', [StockAnalysisController::class, 'index'])->name('index');
        });

        Route::group(['prefix' => 'import', 'as' => 'import.', 'middleware' => 'admin'], function () {
            Route::get('/', [StockImportController::class, 'index'])->name('index');
            Route::post('/store', [StockImportController::class, 'importStocks'])->name('store');
        });

        Route::group(['middleware' => 'admin'], function () {
            Route::get('/create', [StockController::class, 'create'])->name('create');
            Route::post('/', [StockController::class, 'store'])->name('store');
            Route::get('/{stock:symbol}/edit', [StockController::class, 'edit'])->name('edit');
            Route::put('/{stock}', [StockController::class, 'update'])->name('update');
            Route::delete('/{stock}', [StockController::class, 'destroy'])->name('destroy');
        });

        Route::get('/', [StockController::class, 'index'])->name('index');
        Route::get('/{stock:symbol}', [StockController::class, 'show'])->name('show');
    });

    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::get('/{id}', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProfileController::class, 'update'])->name('update');
    });

    Route::group(['prefix' => 'users', 'as' => 'users.', 'middleware' => 'admin'], function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit/', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'maintenance', 'as' => 'maintenance.', 'middleware' => 'admin'], function () {
        Route::group(['prefix' => 'cache', 'as' => 'cache.'], function () {
            Route::get('enable', [MaintenanceController::class, 'enableCache'])->name('enable');
            Route::get('clear', [MaintenanceController::class, 'clearCache'])->name('clear');
        });

        Route::group(['prefix' => 'migration', 'as' => 'migration'], function () {
            Route::get('reset', [MaintenanceController::class, 'resetMigration'])->name('reset');
        });

        Route::group(['prefix' => 'logs', 'as' => 'logs.'], function () {
            Route::get('/', [LogController::class, 'index'])->name('index');
            Route::delete('/{id}', [LogController::class, 'destroy'])->name('destroy');
            Route::post('/truncate', [LogController::class, 'dropAll'])->name('truncate');
        });
    });
});

Route::get('/', function () {
    return redirect()->route('stocks.index');
});