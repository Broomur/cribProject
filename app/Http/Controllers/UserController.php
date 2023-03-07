<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Controller;
use Illuminate\Http\Request;
use App\Mail\UserAccountDelete;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return Inertia::render('Account/Edit', [
            'user' => User::select('name', 'email', 'id')->where('id', $user->id)->first(),
        ]);
    }

    /**
     * Edit the current user's data in storage.
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'firstname' => 'required|bail|string|alpha_dash|max:110',
            'lastname' => 'required|bail|string|alpha_dash|max:110',
            'email' => ['bail', 'email', Rule::unique('users')->ignore($user->id)],
        ]);
        
        $nameUpdate = User::where('id', $user->id)
                        ->update(['name' => $validated['firstname'] . ' ' . $validated['lastname'], 'email' => $validated['email']]);

        return Redirect::route('account_options', $user->id)->with('message', 'Votre compte a été mis à jour.');
    }

    /**
     * Edit the current user's password.
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function passwdUpdate(Request $request, User $user)
    {
        $validated = $request->validate([
            'old_passwd' => 'required',
            'new_passwd' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (!Hash::check($validated['old_passwd'], auth()->user()->password))
        {
            return back()->with('error', 'L\'ancien mot de passe est incorrect.');
        }

        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($validated['new_passwd'])
        ]);

        return back()->with('message', 'Le mot de passe a été changé.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (Team::where('name', $user->name)->exists()) {
            Team::where('name', $user->name)->delete();
        }
        Mail::to($user->email)->send(new UserAccountDelete($user));
        
        foreach ($user->children as $child) {
            $child_id = $child->pivot->child_id;
            $findChild = Child::find($child_id);
            if (count($findChild->users) === 1) {
                Child::find($child_id)->delete();
            }
        }
        
        $user->roles()->updateExistingPivot($user->roles, ['role_id' => '1']);
        $user->update([
            'name' => 'xxxxxxxx',
            'email' => Hash::make($user->email),
            'active' => 0,
            ]);

        $team = Team::where('name', $user->name)->delete();

        
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return Redirect::route('home')->with('message', 'Votre compte a été supprimé à votre demande.');
    }
}
