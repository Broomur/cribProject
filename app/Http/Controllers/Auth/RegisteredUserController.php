<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Password;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:55',
            'lastname' => 'required|string|max:55',
            'email' => 'required|string|email:rfc,dns|max:85|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'rgpd' => 'required|accepted',
            'children' => 'array',
            'children.*.firstname' => 'nullable|string|max:55',
            'children.*.lastname' => 'nullable|string|max:55',
            'children.*.birthday' => 'nullable|date',
        ]);

        $type = $request->type === true ? 'pro' : 'parent';

        $user = User::create([
            'name' => $validated['firstname'] .' '. $validated['lastname'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'type' => $type,
            'rgpd_accepted' => 1,
        ]);

        if ($user->type === 'parent') {
            foreach ($validated['children'] as $part) {
                $control = DB::table('children')->where(['firstname' => $part['firstname']], ['lastname' => $part['lastname']], ['birthday' => $part['birthday']])->exists();
                if ($control) {
                    $child = DB::table('children')->where(['firstname' => $part['firstname']], ['name' => $part['lastname']], ['birthday' => $part['birthday']])->first();
                } else {
                    $child = Child::create(['firstname' => $part['firstname'], 'lastname' => $part['lastname'], 'birthday' => $part['birthday']]);
                }
                $user->children()->attach($user->id, [ 'child_id' => $child->id ]);
            };
        }

        $user->roles()->attach($user->id, [ 'role_id' => 2 ]);

        event(new Registered($user));
        Auth::login($user);
        return redirect('verify-email');
    }
}
