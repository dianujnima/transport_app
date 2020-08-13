<?php

namespace App\Http\Controllers\Api;

use App\Models\UserLogin;
use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Password;

class AuthController extends ApiController
{
    public function login(Request $request){
        $msg = null;
        $data = [];
        $status = false;
        if($request->type === 'api'){
            $credentials = $request->only(['username', 'password']);
            try {
                if (!$token = \JWTAuth::attempt($credentials)) {
                    throw new \Exception('invalid_credentials');
                }
                $data = array('token' => $token, 'user' => auth()->user());
                $status = true;
                $msg = 'You have successfully logged in';
    
            } catch (\Exception $e) {
                $msg = $e->getMessage();
            } catch (JWTException $e) {
                $msg = 'Could not create token';
            }
        }else{
            $user = User::whereUsername($request->username)->whereSignupType($request->type)->first();
            if(!$user){
                $validation = \Validator::make($request->all(), [
                    'first_name' => ['required', 'string', 'max:80'],
                    'last_name' => ['required', 'string', 'max:80'],
                    'username' => ['string', 'max:255', 'unique:users'],
                ]);
        
                if ($validation->fails()) {
                    return api_response(false, null, $validation->errors()->first());
                }

                $status = 'true';
                $user = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'username' => $request->username,
                    'signup_type' => $request->type,
                    'password' => \Hash::make($request->username.time())
                ]);
            }
            $msg = 'You have successfully logged in';
            $data = array('token' => auth('api')->login($user), 'user' => $user);
            $status = 'true';
        }

        if($status === 'true'){
            UserLogin::create([
                'user_id' => $data['user']->id,
                'platform' => $request->header('platform'),
                'device_token' => $request->header('devicetoken'),
                'user_agent' => $request->header('User-Agent'),
                'login_token' => $data['token'],
                'ip' => $request->ip(),
            ]);
        }

        return api_response($status, $data, $msg);
    }

    public function register(Request $request){
        $validation = \Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'username' => ['string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validation->fails()) {
            return api_response(false, null, $validation->errors()->first());
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'phone_no' => $request->username ?? null,
            'signup_type' => 'api',
            'password' => \Hash::make($request->password)
        ]);

        $token = auth('api')->login($user);
        
        UserLogin::create([
            'user_id' => $user->id,
            'platform' => $request->header('platform'),
            'device_token' => $request->header('devicetoken'),
            'user_agent' => $request->header('User-Agent'),
            'login_token' => $token,
            'ip' => $request->ip(),
        ]);

        $data = array(
            'token' => $token,
            'user' => $user
        );
        return api_response(true, $data, null);
    }

    public function logout(Request $request){
        $token = \JWTAuth::getToken()->get();
        UserLogin::whereLoginToken($token)->whereUserId($this->current_user()->id)->update(['logout_at' => now()]);
        auth('api')->logout();
        return api_response(true, null, 'You are successfully logged out');
    }
    
    public function refresh(){
        $old_token = \JWTAuth::getToken()->get();
        $user_id = $this->current_user()->id;
        $token = auth('api')->refresh();
        UserLogin::whereLoginToken($old_token)->whereUserId($user_id)->update(['login_token' => $token]);
        return api_response(true, array('token' => $token));
    }

    public function update_profile(Request $request){
        $validation = \Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'address' => ['string', 'max:80'],
            'city' => ['string', 'max:80'],
            'state' => ['string', 'max:80'],
            'zipcode' => ['string', 'max:80'],
            'last_name' => ['string', 'max:80'],
            'phone_no' => ['max:40'],
        ]);

        if ($validation->fails()) {
            return api_response(false, null, $validation->errors()->first());
        }

        $profile_img = null;
        if($request->hasFile('profile_image')){
            $profile_img = \CommonHelpers::uploadSingleFile($request->file('profile_image'), 'upload/profile_images/');
            if (is_array($profile_img)) {
                return api_response(false, null, $profile_img['error']);
            }
        }

        $user = $this->current_user();
        
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->zipcode = $request->zipcode;
        $user->phone_no = $request->phone_no;
        $user->image = $profile_img;
        $user->save();

        $data = array(
            'user' => $user
        );
        return api_response(true, $data, 'You profile updated successfully');
    }

    public function change_password(Request $request){
        $validation = \Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|same:password|min:8',
            'password_confirmation' => 'required|same:password|min:8',     
        ]);

        if ($validation->fails()) {
            return api_response(false, null, $validation->errors()->first());
        }
        
        $user = $this->current_user();

        if (!(\Hash::check($request->old_password, $user->password))) {
            return api_response(false, null, 'Your current password does not matches with the password you provided. Please try again.');
        }

        if(strcmp($request->old_password, $request->password) == 0){
            return api_response(false, null, 'New Password cannot be same as your current password. Please choose a different password.');
        }

        $user->password = \Hash::make($request->password);
        $user->save();

        return api_response(true, null, 'Your password have been successfully changed');
    }

    public function forgot_password(Request $request){
        return api_response(false, null, 'Need to implement sms api');
        // $input = $request->all();

        // $validation = \Validator::make($input, [
        //     'email' => "required|email",
        // ]);

        // if ($validation->fails()) {
        //     return api_response(false, null, $validation->errors()->first());
        // }
        // try {
        //     $response = Password::sendResetLink($request->only('email'), function (Message $message) {
        //         $message->subject($this->getEmailSubject());
        //     });
        //     switch ($response) {
        //         case Password::RESET_LINK_SENT:
        //             return api_response(true, null, 'Password has been sent to your email kindly check your inbox and follow the instructions mentioned in the email');
        //         case Password::INVALID_USER:
        //             return api_response(false, null, 'No user found with the given email in our database');
        //         default:
        //             return api_response(false, null, 'This request can\'t be handled at this moment');
        //     }
        // } catch (\Swift_TransportException $ex) {
        //     return api_response(false, null, $ex->getMessage());
        // } catch (\Exception $ex) {
        //     return api_response(false, null, $ex->getMessage());
        // }
    }
}
