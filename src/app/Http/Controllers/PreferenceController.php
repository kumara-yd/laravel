<?php

namespace App\Http\Controllers;

use App\Models\Preference;
use Illuminate\Http\Request;

class PreferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->setRule('preferences.read');

        $preferences = Preference::all();
        return view('preferences.index', compact('preferences'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->setRule('preferences.create');

        $create = true;
    
        return view('preferences.edit', compact('create'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->setRule('users.create');

        $request->validate([
            'name' => 'required|max:150',
            'group' => 'required|max:150',
            'value' => 'required|max:255',
        ]);

        $preference = new Preference();
        $preference->name = $request->name;
        $preference->group = $request->group;
        $preference->value = $request->value;
        $preference->save();

        return redirect()->back()->with('success', __('app.notif.successSave'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Preference $preference)
    {
        $this->setRule('preferences.update');

        return view('preferences.edit', compact('preference'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Preference $preference)
    {
        $this->setRule('users.update');

        $request->validate([
            'name' => 'required|max:150',
            'group' => 'required|max:150',
            'value' => 'required|max:255',
        ]);

        $preference->update($request->all());

        return redirect()->back()->with('success', __('app.notif.successUpdate'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Preference $preference)
    {
        $this->setRule('users.delete');

        $preference->delete();
        
        return redirect('preferences')->with('success', __('app.notif.successDelete'));
    }
}
