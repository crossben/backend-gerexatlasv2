<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function contact(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:20',
        'subject' => 'required|string|max:255',
        'message' => 'required|string|max:5000',
    ]);

    Contact::create($validated);

    return response()->json([
        'status' => 'success',
        'message' => 'Contact message sent successfully!',
    ]);
}
    public function getContacts()
    {
        $contacts = Contact::all();
        return response()->json($contacts);
    }

    public function getContactById($id)
    {
        $contact = Contact::findOrFail($id);
        return response()->json($contact);
    }

    public function deleteContact($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return response()->json(['message' => 'Contact deleted successfully']);
    }
    public function updateContact(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);
        $contact->update($validated);
        return response()->json(['message' => 'Contact updated successfully']);
    }
    public function getContactsByEmail($email)
    {
        $contacts = Contact::where('email', $email)->get();
        return response()->json($contacts);
    }
    public function getContactsByPhone($phone)
    {
        $contacts = Contact::where('phone', $phone)->get();
        return response()->json($contacts);
    }
    public function getContactsBySubject($subject)
    {
        $contacts = Contact::where('subject', 'like', '%' . $subject . '%')->get();
        return response()->json($contacts);
    }
    public function getContactsByName($name)
    {
        $contacts = Contact::where('name', 'like', '%' . $name . '%')->get();
        return response()->json($contacts);
    }
}
