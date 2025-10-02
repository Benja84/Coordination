<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreatedMail;

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
        
        $user = User::create([
            'firstname'  => $request->firstname,
            'lastname'  => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->type
        ]);

        // return redirect('/')->with('success', 'Utilisateur créé avec succès !');
        // Envoi de l'email avec le mot de passe
        try {
            Mail::to($user->email)->send(new UserCreatedMail($user, $request->password));
            
            return redirect('/')->with('success', 'Utilisateur créé avec succès ! Un email a été envoyé avec les identifiants.');
            
        } catch (\Exception $e) {
            
            return redirect('/')->with('warning', 'Utilisateur créé mais l\'email n\'a pas pu être envoyé. Veuillez communiquer le mot de passe manuellement.');
        }
    }
}
