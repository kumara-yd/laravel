<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->setRule('users.read');

        $users = User::all();
        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->setRule('users.create');

        $create = true;
        $roles = Role::all()->pluck('name', 'id');
        return view('user.edit', compact('create', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->setRule('users.create');

        \DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email:rfc,dns|unique:users,email',
                'role' => 'required',
                'roleMultiple' => 'required',
                'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised()],
            ]);
            //
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();
            $user->assignRole($request->role);
            $user->roleMultiple()->sync($request->roleMultiple);
            \DB::commit();
            return redirect()->back()->with('success', __('app.notif.successSave'));
        }catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $this->setRule('users.update');

        $roles = Role::all()->pluck('name', 'id');
        return view('user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->setRule('users.update');

        \DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email:rfc,dns|unique:users,email,' . $user->id,
                'role' => 'required',
                'roleMultiple' => 'required',
            ]);
            //
            if ($request->has('password') && $request->password != '') {
                $request->validate([
                    'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised()],
                ]);
                $user->password = bcrypt($request->password);
            }
            $user->name = $request->name;
            $user->email = $request->email;
            $user->update();
            $user->syncRoles($request->role);
            $user->roleMultiple()->sync($request->roleMultiple);
            \DB::commit();
            return redirect()->back()->with('success', __('app.notif.successUpdate'));
        }catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->setRule('users.delete');

        $user->delete();
        return redirect('users')->with('success', __('app.notif.successDelete'));
    }
}
