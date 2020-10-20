<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Administrator\AdminController;
use App\Models\City;
use App\Services\Slug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends AdminController {

    public function __construct()
    {
        $this->middleware('is_admin');
    }

    public function index() {
        $data = array(
            'title' => 'All Cities',
            'cities' => City::get()
        );
        return view('admin.cities.all_cities')->with($data);
    }

    public function edit(Request $request) {
        $data = array(
            'title' => 'Edit Staff Member',
            'categories' => Category::latest()->get(),
            'category' => Category::hashidFind($request->id)
        );
        return view('admin.cities.all_cities')->with($data);
    }

    public function save(Request $request, Slug $slug) {
        $city_id = @hashids_decode($request->category_id);

        $validator = Validator::make($request->all(), [
            'city' => ['required', 'string', 'max:100', 'unique:cities,name,'.$city_id],
        ]);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }


        if($request->city_id){
            $city = City::hashidFind($request->city_id);
            if($city->title != $request->title){
                $city->slug = $slug->createSlug('cities', $request->city, $city->id);
            }
            $msg = [
                'success' => 'City has been updated',
                'redirect' => route('admin.cities'),
            ];
        }else{
            $city = new City();
            $city->slug = $slug->createSlug('categories', $request->city);
            $msg = [
                'success' => 'City has been added',
                'reload' => true,
            ];
        }

        $city->name = ucwords($request->city);
        $city->province = $request->province;
        $city->save();

        return response()->json($msg);
    }

    public function delete(Request $request)
    {
        $city = City::hashidFind($request->id);
        return response()->json([
            'success' => 'City deleted successfully',
            'remove_tr' => true
        ]);
    }
}
