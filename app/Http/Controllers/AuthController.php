<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\Models\Annonce;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('user');
        return response()->json(['message' => 'Inscription ok.']);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages(['message' => 'Le mail et/ou le mot de passe sont incorrects']);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->getRoleNames(),
        ]);

    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Déconnexion ok']);
    }

    public function user(Request $request)
    {
        return response()->json([
            'id' => $request->user()->id,
            'name' => $request->user()->name,
            'email' => $request->user()->email,
            'roles' => $request->user()->getRoleNames(), // pour le role utilisateur.
        ]);
    }

    public function usersList(){
        $Annonces = User::all();
        return response()->json($Annonces);
    }
}
