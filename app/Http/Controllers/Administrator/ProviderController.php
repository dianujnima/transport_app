<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Administrator\AdminController;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProviderController extends AdminController {

    public function __construct()
    {
        $this->middleware('is_admin',  ['except' => ['update_profile', 'save_profile', 'change_password']]);
    }

    public function index() {
        $data = array(
            'title' => 'All Providers',
            'providers' => Provider::with('user')->has('user')->latest()->get()
        );
        return view('admin.providers.all_providers')->with($data);
    }

    public function add() {
        $data = array(
            'title' => 'Add Provider',
        );
        return view('admin.providers.add_provider')->with($data);
    }

    public function edit(Request $request) {
        $data = array(
            'title' => 'Edit Provider',
            'provider' => Provider::with('user')->hashidFind($request->provider_id)
        );
        return view('admin.providers.add_provider')->with($data);
    }

    public function save(Request $request) {
        $rules = [
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'company_name' => ['required', 'string', 'max:80'],
            'contact_person.name' => ['required', 'string', 'max:80'],
            'contact_person.phone' => ['required', 'string', 'max:80'],
        ];

        if(!$request->provider_id){
            $rules['username'] = ['required', 'string', 'max:255', 'unique:admins'];
            $rules['password'] = ['required', 'string', 'min:2'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }

        if($request->provider_id){
            $provider = Provider::hashidFind($request->provider_id);
            $user = \App\Models\Admin::find($provider->user_id);
            $msg = [
                'success' => 'Provider has been updated',
                'reload' => true,
            ];
        }else{
            $user = new \App\Models\Admin();
            $provider = new Provider();
            $user->is_active = 1;
            $user->username = $request->username;
            $user->user_type = 'provider';
            $user->password = \Hash::make($request->password);
            $msg = [
                'success' => 'Provider has been added',
                'redirect' => route('admin.providers'),
            ];
        }

        if ($request->hasFile('contracts')) {
            $contract_file = \CommonHelpers::uploadSingleFile($contract_file, 'upload/contract_files/');
            if (is_array($contract_file)) {
                return response()->json($contract_file);
            }
            $provider->contract = $contract_file;
        }

        if ($request->hasFile('profile_img')) {
            $profile_img = \CommonHelpers::uploadSingleFile($request->file('profile_img'), 'upload/profile_images/');
            if (is_array($profile_img)) {
                return response()->json($profile_img);
            }
            if (file_exists($provider->image)) {
                @unlink($provider->image);
            }
            $provider->image = $profile_img;
        }
        
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->save();
        
        $provider->user_id = $user->id;
        $provider->name = $request->company_name;
        $provider->address = $request->address;
        $provider->city = $request->city;
        $provider->ntn = $request->company_ntn;
        $provider->phone_nos = $request->phone_nos;
        $provider->contact_person_info = $request->contact_person;
        $provider->save();

        return response()->json($msg);
    }


    public function update_password(Request $request) {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }

        $provider = \App\Models\Admin::find($request->provider_user_id);
        $provider->password = \Hash::make($request->password);
        $provider->save();

        return response()->json([
            'success' => 'Provider\'s password has been updated',
            'reload' => true,
        ]);
    }

    public function delete(Request $request)
    {        
        $provider = Provider::hashidFind($request->provider_id);
        $provider->delete();
        return response()->json([
            'success' => 'Provider deleted successfully',
            'remove_tr' => true
        ]);
    }

    public function updateStatus(Request $request) {
        $provider = \App\Models\Admin::find($request->provider_id);
        $provider->is_active = !$provider->is_active;
        $provider->save();
        return response()->json([
            'success' => 'Provider\'s Status Updated Successfully',
            'reload' => true
        ]);
    }

    public function update_profile(Request $request){
        $data = array(
            'title' => 'Edit Profile',
            'staff' => Provider::find(auth('admin')->user()->id)
        );
        return view('admin.providers.edi_provider')->with($data);
    }

    public function save_profile(Request $request) {
        $rules = [
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }

        $provider = Provider::find(auth('admin')->user()->id);

        if ($request->hasFile('profile_img')) {
            $profile_img = \CommonHelpers::uploadSingleFile($request->file('profile_img'), 'upload/profile_images/');
            if (is_array($profile_img)) {
                return response()->json($profile_img);
            }
            if (file_exists($provider->image)) {
                @unlink($provider->image);
            }
            $provider->image = $profile_img;
        }

        $provider->first_name = $request->first_name;
        $provider->last_name = $request->last_name;
        $provider->save();

        $msg = [
            'success' => 'Your profile has been updated',
            'reload' => true,
        ];

        return response()->json($msg);
    }

    public function change_password(Request $request) {
        $validator = \Validator::make($request->all(), [
            'old_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }

        $msg = [
            'success' => 'Your password has been changed',
            'reload' => true,
        ];

        $provider = Provider::find(auth('admin')->user()->id);

        if (!(\Hash::check($request->old_password, $provider->password))) {
            return response()->json([
                'error' => 'Your current password does not matches with the password you provided. Please try again.',
            ]);
        }

        if(strcmp($request->old_password, $request->password) == 0){
            return response()->json([
                'error' => 'New Password cannot be same as your current password. Please choose a different password.',
            ]);
        }

        $provider->password = \Hash::make($request->password);
        $provider->save();

        return response()->json($msg);
    }
}
