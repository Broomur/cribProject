<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Album;
use App\Models\Child;
use App\Models\Document;
use App\Models\HomeText;
use Illuminate\Http\Request;
use App\Mail\AccountValidation;
use App\Mail\AdminAccountDelete;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class AdminController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Admin/Index', [
            'users' => User::where('email_verified_at', '!=', null)
                ->with('roles')
                ->with('children')
                ->join('role_user', 'users.id', '=', 'role_user.user_id')
                ->where('role_user.role_id', '!=', '1')
                ->orderBy('role_user.role_id', 'asc')
                ->simplePaginate(20),
            'roles' => Role::whereNotIn('name', [
                    'compte désactivé', 
                    'compte inactif', 
                    'parent', 
                    'commission animation', 
                    'commission bio', 
                    'commission recrutement', 
                    'commission logistique', 
                    'admin'])
                ->orderBy('id', 'asc')
                ->get(),
            'texts' => HomeText::get(),
            'introAlbum' => Album::where('name', 'public-presentation')
                ->with('pictures')
                ->get(),
            'welcomeDoc' => Document::where('name', 'public-welcome')
                ->first(),
            'educativeDoc' => Document::where('name', 'public-educative')
                ->first(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param \App\Models\HomeText $hometext
     * @return \Illuminate\Http\Response
     */
    public function edit(HomeText $hometext)
    {
        return Inertia::render('Admin/Edit', [
            'text' => HomeText::where('type', $hometext->type)->first(),
            'album' => Album::where('name', 'public-presentation')
                ->with('pictures')
                ->get(),
            'welcome' => Document::where('name', 'public-welcome')
                ->first(),
            'educative' => Document::where('name', 'public-educative')
                ->first(),
        ]);
    }


    public function update(Request $request, HomeText $hometext)
    {
        $validated = $request->validate([
            'content' => 'string|max:1500|nullable',
        ]);

        $hometext->update([
            'content' => $validated['content'],
        ]);

        return Redirect::route('admin.index')->with('message', 'Le contenu a été mis à jour.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return Inertia::render('Admin/Show', [
            'user' => User::where('id', $user->id)
                        ->with('roles')
                        ->with('children')
                        ->join('role_user', 'users.id', '=', 'role_user.user_id')
                        ->first(),
            'roles' => Role::whereNotIn('name', [
                'compte désactivé', 
                'compte inactif', 
                'parent', 
                'commission animation', 
                'commission bio', 
                'commission recrutement', 
                'commission logistique', 
                'admin'])
            ->orderBy('id', 'asc')
            ->get(),
        ]);
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
        Mail::to($user->email)->send(new AdminAccountDelete($user));

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
            'active' => false,
            ]);

        return Redirect::route('admin.index')->with('message', 'Utilisateur supprimé.');
    }

    /**
     * Activate the specified account (parent version).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function activate_parent(Request $request, User $user)
    {
        $roles = Role::where('name', $request->role)->first();

        $user->roles()->where('name', $roles->name)->updateExistingPivot($user->roles, ['role_id' => $roles->id]);

        User::where('id', $user->id)->update(['active' => 1]);

       Mail::to($user->email)->send(new AccountValidation($user));

        return Redirect::route('admin.index')->with('message', 'Rôle mis à jour.');
    }

    /**
     * Activate the specified account (pro version).
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function activate_pro(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'exists:roles,name',
        ]);
        
        $roles = Role::where('name', $validated['role'])->first();

        $user->roles()->where('name', $roles->name)->updateExistingPivot($user->roles, ['role_id' => $roles->id]);

        User::where('id', $user->id)->update(['active' => 1]);

        Mail::to($user->email)->send(new AccountValidation($user));

        return Redirect::route('admin.index')->with('message', 'Rôle mis à jour.');
    }
}
