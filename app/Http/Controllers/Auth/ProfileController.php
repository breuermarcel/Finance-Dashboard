<?php


namespace App\Http\Controllers\Auth;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController
{
    public function edit()
    {
        return view('auth.edit', ['profile' => Auth::user()]);
    }

    /**
     * @param Request $request
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        if ($request->has('password')) {
            $validator = Validator::make($request->all(), [
                'firstname' => ['required', 'string', 'max:255'],
                'lastname' => ['required', 'string', 'max:255'],
                'password' => ['string', 'confirmed', 'min:8']
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $validated = $validator->validate();
            $validated['password'] = Hash::make($validated['password']);
        } else {
            $validator = Validator::make($request->all(), [
                'firstname' => ['required', 'string', 'max:255'],
                'lastname' => ['required', 'string', 'max:255']
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $validated = $validator->validate();
        }

        $user->update($validated);

        return redirect()->route('dashboard')->with('success', 'Profile updated.');
    }
}
