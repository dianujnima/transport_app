<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Administrator\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificaitonController extends AdminController {

    public function index() {
        $data = array(
            'title' => 'All Notifications',
            'notifications' => auth('admin')->user()->notifications
        );
        return view('admin.notifications.all_notifications')->with($data);
    }

    public function mark_all_read(Request $request){
        auth('admin')->user()->unreadNotifications->markAsRead();
        if($request->expectsJson()){
            return response()->json([
                'success' => 'All Notifications marked as read successfully',
            ]);
        }
        if ($request->wantsJson()) {
            return response()->json([
                'success' => 'Notifications marked as read successfully',
                'reload' => 'true'
            ]);
        }

        return redirect()->back();
    }

    public function mark_single_notification_read($id){
        auth('admin')->user()->unreadNotifications->where('id', $id)->markAsRead();
        return response()->json([
            'success' => 'Notification marked as read successfully',
            'reload' => 'true'
        ]);
    }

    public function delete_notifications(){
        auth('admin')->user()->notifications()->delete();
        return response()->json([
            'success' => 'Notifications deleted successfully',
            'reload' => 'true'
        ]);
    }
}
