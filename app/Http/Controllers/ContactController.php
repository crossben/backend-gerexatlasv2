<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function Contact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'email|max:255',
            'phone' => 'string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Use the Contact model to create the contact
        $contact = Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Contact message sent successfully!'
        ], 200);
    }
}
