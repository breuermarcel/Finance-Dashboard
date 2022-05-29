<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;


class LogController extends Controller
{
    public function index()
    {
        $logs = Log::all()->orderBy('created_at', 'DESC');

        return view('logs.list', compact('logs'));
    }

    /**
     * @param Log $log
     * @return mixed
     */
    public function destroy(Log $log)
    {
        $log->delete();

        return redirect()->route('logs.index')->with('success', 'Problem solved.');
    }

    /**
     * @param Log $log
     * @return mixed
     */
    public function dropAll(Log $log)
    {
        $log->truncate();

        return redirect()->route('logs.index')->with('success', 'Logs deleted.');
    }
}