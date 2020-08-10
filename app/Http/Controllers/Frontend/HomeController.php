<?php

namespace App\Http\Controllers\Frontend;

use App\Contact;
use App\Http\Controllers\Controller;
use CommonHelpers;
use Illuminate\Http\Request;

class HomeController extends Controller {

    public function index() {
        return redirect(route('admin.home'));
        $data = array(
            'title' => 'Home Page',
        );
        return view('front.home')->with($data);
    }

    public function privacy_policy() {
        $data = array(
            'title' => 'Privacy Policy',
        );
        return view('front.pages.privacy_policy')->with($data);
    }

    public function terms_conditions() {
        $data = array(
            'title' => 'Terms Conditions',
        );
        return view('front.pages.terms_conditions')->with($data);
    }

    public function about() {
        $data = array(
            'title' => 'About Us',
        );
        return view('front.pages.about')->with($data);
    }

    public function contact() {
        $data = array(
            'title' => 'Contact Us',
        );
        return view('front.pages.contact')->with($data);
    }

    public function contact_save(Request $request) {
        $contact = new Contact();
        $contact->ip = $request->ip();
        if(auth('user')->check()){
            $contact->user_id = auth('user')->user()->id;
        }
        $contact->name = $request->form_name;
        $contact->email = $request->form_email;
        $contact->phone = $request->form_phone;
        $contact->message = $request->form_message;
        $contact->save();

        return response()->json([
            'success' => 'Thanks for contacting us we will contact you soon',
            'reload' => true,
        ]);
    }
}
