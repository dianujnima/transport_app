<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\UsersSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {

    public function mark_all_read(Request $request){
        auth('user')->user()->unreadNotifications->markAsRead();
        if($request->expectsJson()){
            return response()->json([
                'success' => 'All Notifications marked as read successfully',
            ]);
        }
        return redirect()->back();
    }

    public function mark_single_notification_read($id){
        auth('user')->user()->unreadNotifications->where('id', $id)->markAsRead();
        return response()->json([
            'success' => 'Notification marked as read successfully',
            'reload' => 'true'
        ]);
    }

    public function delete_notifications(){
        auth('user')->user()->notifications()->delete();
        return redirect()->back();
    }

    public function profile(){
        $data = array(
            'title' => 'User Profile',
            'user' => auth('user')->user(),
        );
        return view('front.user.user_profile')->with($data);
    }

    public function change_password(Request $request){

        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|same:password|min:8',
            'password_confirmation' => 'required|same:password|min:8',     
        ]);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }
        
        $user = auth('user')->user();

        if (!(\Hash::check($request->old_password, $user->password))) {
            return response()->json([
                'error' => 'Your current password does not matches with the password you provided. Please try again.',
            ]);
        }

        if(strcmp($request->old_password, $request->password) == 0){
            return response()->json([
                'error' => 'New Password cannot be same as your current password. Please choose a different password.',
            ]);
        }

        $user->password = \Hash::make($request->password);
        $user->save();

        return response()->json([
            'success' => 'Your password have been successfully changed.',
            'reload' => true
        ]);
        
    }

    public function update_profile(Request $request){

        $user = auth('user')->user();
        if ($request->hasFile('cover_image')) {
            $cover_image = \CommonHelpers::uploadSingleFile($request->file('cover_image'), 'upload/cover_image/');
            if (is_array($cover_image)) {
                return response()->json($cover_image);
            }
            if (file_exists($user->image)) {
                @unlink($user->image);
            }
            $user->cover_image = $cover_image;
            $user->description = $request->description;
            $user->save();
            return response()->json([
                'success' => 'Your profile updated successfully.',
                'reload' => true
            ]);

        }

        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'address' => ['required', 'string', 'max:100'],
            'phone_no' => ['max:40'],
            'city' => ['required', 'string', 'max:80'],
            'state' => ['required', 'string', 'max:80'],
            'zipcode' => ['required', 'string', 'max:80'],
        ]);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }
        
       
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->address = $request->address;
        $user->phone_no = $request->phone_no;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->zipcode = $request->zipcode;


        if ($request->hasFile('profile_img')) {
            $profile_img = \CommonHelpers::uploadSingleFile($request->file('profile_img'), 'upload/profile_images/');
            if (is_array($profile_img)) {
                return response()->json($profile_img);
            }
            if (file_exists($user->image)) {
                @unlink($user->image);
            }
            $user->image = $profile_img;
        }

        $user->save();

        return response()->json([
            'success' => 'Your profile updated successfully.',
            'reload' => true
        ]);
    }
}
