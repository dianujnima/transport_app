<?php

namespace App\Http\Controllers\Administrator;

use App\Models\Admin as Staff;
use App\Http\Controllers\Administrator\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StaffController extends AdminController {

    public function __construct()
    {
        $this->middleware('is_admin',  ['except' => ['update_profile', 'save_profile', 'change_password']]);
    }

    public function index() {
        $data = array(
            'title' => 'All Staff Members',
            'staffs' => Staff::where('id', '!=', auth('admin')->user()->id)->latest()->get()
        );
        return view('admin.staffs.all_staffs')->with($data);
    }

    public function add() {
        $data = array(
            'title' => 'Add Staff Member',
        );
        return view('admin.staffs.add_staff')->with($data);
    }

    public function edit(Request $request) {
        $data = array(
            'title' => 'Edit Staff Member',
            'staff' => Staff::hashidFind($request->staff_id)
        );
        return view('admin.staffs.add_staff')->with($data);
    }

    public function save(Request $request) {
        $rules = [
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'user_role' => ['required', 'string', 'in:admin,sales'],
        ];

        if(!$request->staff_id){
            $rules['email'] = ['required', 'string', 'email', 'max:255', 'unique:admins'];
            $rules['password'] = ['required', 'string', 'min:8'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }


        if($request->staff_id){
            $staff = Staff::hashidFind($request->staff_id);
            $msg = [
                'success' => 'Staff member has been updated',
                'reload' => true,
            ];
        }else{
            $staff = new Staff();
            $staff->added_by_id = auth('admin')->user()->id;
            $staff->is_active = 1;
            $staff->email = $request->email;
            $staff->password = \Hash::make($request->password);
            $msg = [
                'success' => 'Staff member has been added',
                'redirect' => route('admin.staffs'),
            ];
        }

        if ($request->hasFile('profile_img')) {
            $profile_img = \CommonHelpers::uploadSingleFile($request->file('profile_img'), 'upload/profile_images/');
            if (is_array($profile_img)) {
                return response()->json($profile_img);
            }
            if (file_exists($staff->image)) {
                @unlink($staff->image);
            }
            $staff->image = $profile_img;
        }
        
        $staff->first_name = $request->first_name;
        $staff->last_name = $request->last_name;
        $staff->user_role = $request->user_role;

        $staff->save();


        return response()->json($msg);
    }


    public function update_password(Request $request) {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }

        $msg = [
            'success' => 'Staff member\'s password has been updated',
            'reload' => true,
        ];

        $staff = Staff::hashidFind($request->staff_id);
        $staff->password = \Hash::make($request->password);
        $staff->save();

        return response()->json($msg);
    }

    public function delete(Request $request)
    {        
        $staff = Staff::hashidFind($request->staff_id);
        $staff->delete();
        return response()->json([
            'success' => 'Staff member deleted successfully',
            'remove_tr' => true
        ]);
    }

    public function updateStatus(Request $request) {
        $staff = Staff::hashidFind($request->staff_id);
        $staff->is_active = !$staff->is_active;
        $staff->save();
        return response()->json([
            'success' => 'Staff Member\'s Status Updated Successfully',
            'reload' => true
        ]);
    }

    public function update_profile(Request $request){
        $data = array(
            'title' => 'Edit Profile',
            'staff' => Staff::find(auth('admin')->user()->id)
        );
        return view('admin.staffs.edit_profile')->with($data);
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

        $staff = Staff::find(auth('admin')->user()->id);

        if ($request->hasFile('profile_img')) {
            $profile_img = \CommonHelpers::uploadSingleFile($request->file('profile_img'), 'upload/profile_images/');
            if (is_array($profile_img)) {
                return response()->json($profile_img);
            }
            if (file_exists($staff->image)) {
                @unlink($staff->image);
            }
            $staff->image = $profile_img;
        }

        $staff->first_name = $request->first_name;
        $staff->last_name = $request->last_name;
        $staff->save();

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

        $staff = Staff::find(auth('admin')->user()->id);

        if (!(\Hash::check($request->old_password, $staff->password))) {
            return response()->json([
                'error' => 'Your current password does not matches with the password you provided. Please try again.',
            ]);
        }

        if(strcmp($request->old_password, $request->password) == 0){
            return response()->json([
                'error' => 'New Password cannot be same as your current password. Please choose a different password.',
            ]);
        }

        $staff->password = \Hash::make($request->password);
        $staff->save();

        return response()->json($msg);
    }
}
