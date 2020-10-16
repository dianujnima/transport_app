<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Administrator\AdminController;
use App\Models\Category;
use App\Models\City;
use App\Models\Provider;
use App\Models\ProviderSchedule;
use App\Models\ScheduleSeat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends AdminController {

    public function __construct()
    {
        // $this->middleware('is_admin',  ['except' => ['update_profile', 'save_profile', 'change_password']]);
    }

    public function index() {
        $data = array(
            'title' => 'All Provider Schedules',
            'schedules' => ProviderSchedule::with(['provider', 'category', 'seats'])->has('provider')->orderBy('updated_at', 'desc')->get()
        );
        return view('admin.schedules.all_schedules')->with($data);
    }

    public function add() {
        $providers = Provider::all();
        $data = array(
            'title' => 'Add Provider Schedule',
            'providers' => $providers,
            'categories' => Category::AllActive()->get(),
            'cities' => City::all(),
            'user' => auth('admin')->user()
        );

        return view('admin.schedules.add_schedule')->with($data);
    }

    public function edit(Request $request) {
        $providers = Provider::all();
        $data = array(
            'title' => 'Edit Provider',
            'schedule' => ProviderSchedule::with('seats')->hashidFind($request->schedule_id),
            'providers' => $providers,
            'categories' => Category::AllActive()->get(),
            'cities' => City::all(),
            'user' => auth('admin')->user()
        );
        return view('admin.schedules.edit_schedule')->with($data);
    }

    public function save(Request $request) {
        $rules = [
            'provider' => ['required', 'integer'],
            'vehicle_type' => ['required', 'string', 'max:80', 'in:luxury,super_luxury,standard'],
            'category' => ['required', 'integer'],
            'seats' => ['required', 'array'],
            'route_from' => ['required'],
            'route_to' => ['required'],
            'schedule_date' => ['required', 'date'],
            'arrival_time' => ['required', 'date_format:H:i'],
            'destination_time' => ['required', 'date_format:H:i'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }

        $user = auth()->user();

        $schedule = new ProviderSchedule();
        $user->added_by = $user->id;
        if ($request->hasFile('image')) {
            $vehicle_image = \CommonHelpers::uploadSingleFile($request->file('image'), 'upload/vehicle_images/');
            if (is_array($vehicle_image)) {
                return response()->json($vehicle_image);
            }
            $schedule->image = $vehicle_image;
        }

        if($user->is_admin){
            $schedule->provider_id = $request->provider;
        }else{
            $schedule->provider_id = $user->provider->id;
        }
        $schedule->category_id = $request->category;
        $schedule->type = $request->vehicle_type;
        $schedule->total_seats = collect($request->seats)->sum('qty');
        $schedule->route_from = $request->route_from;
        $schedule->route_to = $request->route_to;
        $schedule->date = $request->schedule_date;
        $schedule->arrival_time = $request->arrival_time;
        $schedule->destination_time = $request->destination_time;
        $schedule->desc = $request->desc;
        $schedule->save();

        $seats = [];
        $date = now();
        foreach($request->seats as $k => $seat){
            if(isset($seat['check'])){
                $seats[] = array(
                    'schedule_id' => $schedule->id,
                    'seat_type' => $k,
                    'total_seats' => $seat['qty'],
                    'cost' => $seat['price'],
                    'created_at' => $date,
                    'updated_at' => $date
                );
            }
        }
        ScheduleSeat::insert($seats);

        return response()->json([
            'success' => 'Provider Schedule has been added',
            'redirect' => route('admin.schedules'),
        ]);
    }

    public function save_edit(Request $request) {
        $rules = [
            'provider' => ['required', 'integer'],
            'vehicle_type' => ['required', 'string', 'max:80', 'in:luxury,super_luxury,standard'],
            'category' => ['required', 'integer'],
            'seats' => ['required', 'array'],
            'route_from' => ['required'],
            'route_to' => ['required'],
            'schedule_date' => ['required', 'date'],
            'arrival_time' => ['required', 'date_format:H:i'],
            'destination_time' => ['required', 'date_format:H:i'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }

        $user = auth()->user();

        $schedule = ProviderSchedule::hashidFind($request->schedule_id);
        if ($request->hasFile('image')) {
            $vehicle_image = \CommonHelpers::uploadSingleFile($request->file('image'), 'upload/vehicle_images/');
            if (is_array($vehicle_image)) {
                return response()->json($vehicle_image);
            }
            $schedule->image = $vehicle_image;
        }

        if($user->is_admin){
            $schedule->provider_id = $request->provider;
        }

        $schedule->category_id = $request->category;
        $schedule->type = $request->vehicle_type;
        $schedule->total_seats = collect($request->seats)->sum('qty');
        $schedule->route_from = $request->route_from;
        $schedule->route_to = $request->route_to;
        $schedule->date = $request->schedule_date;
        $schedule->arrival_time = $request->arrival_time;
        $schedule->destination_time = $request->destination_time;
        $schedule->desc = $request->desc;
        $schedule->save();

        $seats = [];
        $date = now();
        foreach($request->seats as $k => $seat){
            if(isset($seat['check'])){
                $seats[] = array(
                    'schedule_id' => $schedule->id,
                    'seat_type' => $k,
                    'total_seats' => $seat['qty'],
                    'cost' => $seat['price'],
                    'updated_at' => $date
                );
            }
        }
        ScheduleSeat::whereScheduleId($schedule->id)->delete();
        ScheduleSeat::insert($seats);

        return response()->json([
            'success' => 'Provider Schedule has been updated',
            'redirect' => route('admin.schedules'),
        ]);
    }

    public function delete(Request $request)
    {        
        $schedule = ProviderSchedule::hashidFind($request->schedule_id);
        $booking = \App\Models\Ticket::whereScheduleId($schedule->id)->count();
        if($booking > 0){
            return response()->json([
                'error' => 'You can\'t remove this Schedule. There are bookings associated with this schedule!',
            ]);
        }
        $schedule->delete();
        return response()->json([
            'success' => 'Provider Schedule deleted successfully',
            'remove_tr' => true
        ]);
    }
}
