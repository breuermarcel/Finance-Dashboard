<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('auth.users.list', compact('users'));
    }

    public function create()
    {
        return view('auth.users.create');
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'password' => ['string', 'confirmed', 'min:8'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users']
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $validated['password'] = Hash::make($request->password);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User created.');
    }

    /**
     * @param User $user
     */
    public function edit(User $user)
    {
        return view('auth.users.edit', ['user' => $user]);
    }

    /**
     * @param User $user
     * @param Request $request
     */
    public function update(User $user, Request $request)
    {
        if ($request->has('password')) {
            if ($request->email !== $user->email) {
                $validator = Validator::make($request->all(), [
                    'firstname' => ['required', 'string', 'max:255'],
                    'lastname' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    'password' => ['string', 'confirmed', 'min:8']
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'firstname' => ['required', 'string', 'max:255'],
                    'lastname' => ['required', 'string', 'max:255'],
                    'password' => ['string', 'confirmed', 'min:8']
                ]);
            }

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $validated = $validator->validate();
            $validated['password'] = Hash::make($validated['password']);
        } else if ($request->email !== $user->email) {
            $validator = Validator::make($request->all(), [
                'firstname' => ['required', 'string', 'max:255'],
                'lastname' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users']
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $validated = $validator->validate();
        } else {
            $validator = Validator::make($request->all(), [
                'firstname' => ['required', 'string', 'max:255'],
                'lastname' => ['required', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $validated = $validator->validate();
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Profile updated.');
    }

    /**
     * @param User $user
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Profile deleted.');
    }
}
