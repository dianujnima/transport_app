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
            'categories' => \App\Models\Category::whereIsActive(1)->get(),
            'operators' => \App\Models\Provider::with('user')->has('user')->latest()->get(),
            'timings' => $this->timings(),
            'bus_types' => [ 'standard' => 'Standard', 'luxury' => 'Luxury', 'super_luxury' => 'Super Luxury'],
            'notifications' => $user->unreadNotifications()->limit(10)->get(),
        );

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

    private function timings(){
        $timings = array(
            'any' => ['name' => 'Any Timing', 'from' => '00:00', 'to' => '23:59'],
            'early_morning' => ['name' => 'Early Morning (12:00 AM - 05:59 AM)', 'from' => '00:00', 'to' => '05:59'],
            'morning' => ['name' => 'Morning (06:00 AM - 11:59 AM)', 'from' => '06:00', 'to' => '11:59'],
            'afternoon' => ['name' => 'Afternoon (12:00 PM - 04:59 PM)', 'from' => '12:00', 'to' => '16:59'],
            'evening' => ['name' => 'Evening (05:00 PM - 07:59 PM)', 'from' => '17:00', 'to' => '18:59'],
            'night' => ['name' => 'Night (09:00 PM - 11:59 PM)', 'from' => '19:00', 'to' => '23:59'],
        );
        return $timings;
    }
    
}
