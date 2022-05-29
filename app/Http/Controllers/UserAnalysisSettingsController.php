<?php

namespace App\Http\Controllers;

use App\Models\UserAnalysisSettings;
use Illuminate\Http\Request;
use App\Models\User;

class UserAnalysisSettingsController extends Controller
{
    /**
     * @param User $user
     */
    public function index(User $user)
    {
        $settings = $user->find(auth()->id())->stockAnalysisPreferences()->orderBy('indicator')->get();

        if ($settings->count() < 1) {
            return redirect()->route('stocks.analysis.settings.create')->with('error', 'Please add at least one criteria.');
        }

        return view('analysis.settings.list', compact('settings'));
    }

    /**
     * @param UserAnalysisSettings $stockPreference
     */
    public function create(UserAnalysisSettings $stockPreference)
    {
        return view('analysis.settings.create', [
            'indicators' => $stockPreference->getIndicators(),
            'expressions' => $stockPreference->expressions
        ]);
    }

    /**
     * @param Request $request
     */
    public function store(Request $request, UserAnalysisSettings $stockPreference)
    {
        $validator = Validor::make($request->all(), [
            'indicator' => ['required', 'string'],
            'expression' => ['required', 'string'],
            'value' => ['required']
        ]);

        if ($validator->fails()) {
            return redirect()->route('stocks.analysis.settings.create')->withErrors($validator)->withInput();
        }

        $validated = $validator->validate();
        $validated['user_id'] = auth()->id();

        foreach($stockPreference->getIndicators() as $indicator) {
            if ($indicator === $request->indicator) {
                User::findOrFail(auth()->id())->stockAnalysisPreferences()->save(new UserAnalysisSettings($validated));
                return redirect()->route('stocks.analysis.settings.index')->with('success', 'Setting created.');
            }
        }

        abort(403, 'Indicator is not available.');
    }

    public function destroy(UserAnalysisSettings $setting)
    {
        $setting->delete();

        return redirect()->route('stocks.analysis.settings.index')->with('success', 'Setting deleted.');
    }
}
