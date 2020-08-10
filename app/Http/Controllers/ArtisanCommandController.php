<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class ArtisanCommandController extends Controller
{
    public function config_cache(){
        \Artisan::call('config:cache');
        return "Config has been cached";
    }
    
    public function route_cache(){
        \Artisan::call('route:cache');
        return "Route has been cached";
    }
    
    public function cache_clear(){
        \Artisan::call('cache:clear');
        return "Cache is cleared";
    }

    public function route_list(){
        $routeCollection = \Route::getRoutes();
        // return "Cache is cleared";
    }
}