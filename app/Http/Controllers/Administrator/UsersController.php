<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Administrator\AdminController;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends AdminController {

    public function __construct()
    {
        $this->middleware('is_admin');
    }

    public function index() {
        $data = array(
            'title' => 'Users',
            'users' => User::latest()->get()
        );
        return view('admin.users.all_users')->with($data);
    }

    public function change_status(Request $request) {
        $user = User::hashidFind($request->id);
        $user->is_active = !$user->is_active;
        $user->save();
        return response()->json([
            'success' => 'User\'s Status Updated Successfully',
            'reload' => true
        ]);
    }

}
