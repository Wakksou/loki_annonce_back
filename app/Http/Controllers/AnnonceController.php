<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annonce;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AnnonceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Annonces = Annonce::all();
        $Annonces->transform(function ($Annonce) {
            if($Annonce->image) {
                $Annonce->image_url = url('storage/' . $Annonce->image);
            }
            //recup l'user 
            $Annonce->createdBy = $Annonce->users->first() ? $Annonce->users->first()->name : null;
            return $Annonce;
        });
        return response()->json($Annonces);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,gif,webp|max:2048',
            'description' => 'nullable|string',
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('Annonces', 'public');
        } else {
            $imagePath = null;
        }

        $Annonce = Annonce::create([
            'title' => $request->title,
            'category' => $request->category,
            'image' => $imagePath,
            'description' => $request->description,
        ]);

         // Lier l'annonce au user connecté
        $user = $request->user();

        if ($user) {
            $user->annonces()->attach($Annonce->id); // Attacher l'annonce à cet utilisateur
        }

        return response()->json($Annonce);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Annonce = Annonce::find($id);
        if (!$Annonce){
            return response()->json(['erreur' => 'Annonce non trouvé'], 404);
        }
        $Annonce->createdBy = $Annonce->users->first() ? $Annonce->users->first()->name : null;
        if ($Annonce->image) {
            $Annonce->image_url = url('storage/' . $Annonce->image);
        }
        return response()->json($Annonce);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Annonce = Annonce::find($id);
        if(!$Annonce) {
            return response()->json(['erreur' => 'Annonce non trouvé'], 404);
        }
        if($Annonce->image) {
            $imagePath = 'public/' . $Annonce->image;
            if(Storage::exists($imagePath)) {
                Storage::delete($imagePath);
            }
        }

        $Annonce->delete();
        
        return response()->json(['message' => 'Annonce supprimé']);
    }
}