<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{

    public function sendMail(Request $request) 
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'email' => 'required|email|max:255',
        ]);
        $details = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'message' => $validated['message'],
        ];
        $subject = $validated['subject'];

        Mail::to('admin@mail.fr')->send(new ContactMail($details, $subject));

        // return back()->with('success', 'Nous avons reçu votre demande de contact');
        return response()->json(['success' => 'email bien envoyé']);

    }
}
