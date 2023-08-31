<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    private string $menu = 'Contacts';

    public function __construct() {
        view()->share('menu', $this->menu);
    }

    public function index() {
        $contacts = Contact::orderBy('replied', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.contacts.index', [
            'contacts' => $contacts,
        ]);
    }

    public function edit($id) {
        $contact = Contact::find($id);
        if (!$contact) {
            return back()->with('error_message', 'Cannot find contact info.');
        }
        $contact['read'] = true;
        $contact->save();
        return view('admin.contacts.edit', [
            'contact' => $contact,
        ]);
    }

    public function update($id, Request $request) {
        $contact = Contact::find($id);
        if (!$contact) {
            return back()->withInput()->with('error_message', 'Cannot find contact info.');
        }
        $contact['replied'] = !empty($request['reply']);
        $contact->save();
        return back()->with('info_message', 'Contact info has been updated.');
    }
}
