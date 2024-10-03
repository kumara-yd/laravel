<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->setRule('home.read');
        $user = auth()->user();
        return $this->display('operator.profile.edit', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->setRule('home.create');
        \DB::beginTransaction();
        try {
            // validation
            $request->validate([
                'oldpassword' => ['required', 'string', 'min:8'],
                'password' => ['required', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised()],
                'password_confirmation' => ['required', 'string'],
            ]);
            $user = auth()->user();
            // cek old password
            if (!\Hash::check($request->oldpassword, $user->password)) {
                throw new \Exception(__('Old password is not correct'));
            }
            $user->password = bcrypt($request->newpassword);
            $user->save();
            \DB::commit();
        } catch (\Exception $th) {
            \DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }

        return redirect()
            ->route('settings.profile.index', ['tab' => 'password'])
            ->with('success', __('Password updated successfully'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $profile)
    {
        $this->setRule('home.update');
        $user = $profile;
        \DB::beginTransaction();
        try {
            // validation
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'role' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email:rfc,dns', 'max:255', Rule::unique('users')->ignore($user->id)],
                'profileImage' => ['nullable', 'image', 'mimes:jpeg,jpg,png'],
            ]);
            $user->name = $request->name;
            $user->email = $request->email;

            if ($request->hasFile('profileImage')) {
                // upload to storage and rename
                $request->profileImage->storeAs('public/images', md5($user->id) . '.' . $request->profileImage->extension());
                $user->profileImage = md5($user->id) . '.' . $request->profileImage->extension();
            }

            $user->save();
            $role = Role::find($request->role);
            $user->syncRoles($role->name);
            \DB::commit();
        } catch (\Exception $th) {
            \DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }

        return redirect()
            ->route('settings.profile.index', ['tab' => 'editProfile'])
            ->with('success', __('Profile updated successfully'));
    }
}
