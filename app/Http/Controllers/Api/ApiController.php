<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    protected function current_user(){
        return auth('api')->user();
    }

    public function home(){
        $user = $this->current_user();
        $data = array(
            'total_searches' => \App\SavedSearch::whereUserId($user->id)->count(),
            'total_viewed' => \App\PropertyView::whereUserId($user->id)->count(),
            'total_bookmarked' => \App\PropertyBookmark::whereUserId($user->id)->count(),
            'notifications' => $user->unreadNotifications()->limit(10)->get(),
        );

        if (!$user->is_wholesaler) {
            $data['total_properties'] = \App\Property::whereUserId($user->id)->count();
        }

        return api_response(true, $data);
    }

    public function notifications(){
        $user = $this->current_user();
        $data = array(
            'notifications' => $user->notifications()->get(),
        );

        return api_response(true, $data);
    }

    public function mark_all_read(Request $request){
        $user = $this->current_user();
        $user->unreadNotifications->markAsRead();
        return api_response(true, null, 'All Notifications marked as read successfully');
    }

    public function delete_notifications(){
        $user = $this->current_user();
        $user->notifications()->delete();
        return api_response(true, null, 'All Notifications removed successfully');
    }
    
}
