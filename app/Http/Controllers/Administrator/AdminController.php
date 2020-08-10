<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
	public function dashboard() {
        $data = array(
            'title' => 'Dashboad',
        );
        return view('admin.dashboards.admin')->with($data);
    }
}