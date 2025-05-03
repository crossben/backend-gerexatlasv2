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

        // Store the contact message in the database
        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->subject = $request->subject;
        $contact->message = $request->message;
        $contact->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Contact message sent successfully!'
        ], 200);
    }
}
