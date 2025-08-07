<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create()
    {
        return view('admin.new-user');
    }

    public function store(Request $request)
    {
        $request->validate([
            'firstname'     => 'required|string|max:255',
            'lastname'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        
        User::create([
            'firstname'  => $request->firstname,
            'lastname'  => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->type
        ]);

        return redirect('/')->with('success', 'Utilisateur créé avec succès !');
    }
}
