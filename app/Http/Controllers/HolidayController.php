<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use DB;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HolidayController extends Controller 
{
    public function holiday(){
        return view('attendance.holiday');
    }
    public function add_holiday(Request $request){

        $validator = \Validator::make($request -> all(),[

            'holiday_name' =>   'required',
            'holiday_date' =>   'required',
            'holiday_type' =>   'required',
        ]);

        if($validator -> fails()){

            return response()->json([
                'code' => 0,
                'error' => $validator->errors()->toArray()
            ]);
        }
        else{

            $add_holiday = new Holiday();
            $add_holiday -> holiday_name = $request -> holiday_name;
            $add_holiday -> holiday_date = $request -> holiday_date;
            $add_holiday -> holiday_type = $request -> holiday_type;
            $add_holiday -> save();

            return response()->json([

                'status' => 200,
                'msg' => 'Holiday Added Successfully',
		    ]);


        }
    }
    public function holiday_list(Request $request){
        // $holiday_data = Holiday::all();
        $holiday_data = DB::table('holidays')
                    ->select('*')
                    ->orderByRaw("YEAR(holiday_date) ASC")
                    ->orderByRaw("MONTH(holiday_date) ASC")
                    ->get();
        
        $output = '';
        if ($holiday_data->count() > 0) {

            $output .= '<table class="table table-striped table-hover border border-gray" style="width: 100%" id="holiday_table">
            <thead>
                <tr>
                    <th  class="text-center border border-gray">Date</th>
                    <th  class="text-center border border-gray">Day</th>
                    <th  class="text-center border border-gray">Holiday Name</th>
                    <th  class="text-center border border-gray">Holiday Type</th>';
                    if(Auth::user()->hasRole('accounting')){
                        $output.='<th  class="text-center border border-gray" width="5%">Edit</th>
                                <th  class="text-center border border-gray" width="5%">Delete</th>';
                    }
      $output.='</tr>
            </thead>
            <tbody>';
            foreach ($holiday_data as $holiday) {
                $output .=
                '<tr style="font-size: 1rem; vertical-align:middle;">
                    <td>
                        <h5 class="name text-center">'.Carbon::parse($holiday -> holiday_date)->format('M d').'</h5>
                    </td>
                    <td>
                        <h5 class="name text-center">'.Carbon::parse($holiday -> holiday_date)->format('l').'</h5>
                    </td>
                    <td>
                        <h5 class="name text-center">'.$holiday -> holiday_name.'</h5>
                    </td>
                    <td>
                        <h5 class="name text-center">'. $holiday -> holiday_type.'</h5>
                    </td>';
                    if(Auth::user()->hasRole('accounting')){
                          $output.=' <td>  
                                            <a href="#" id="' . $holiday -> id . '" type="button" class="btn-view1 text-success btn-sm mx-1 holiday_edit_icon" data-bs-toggle="modal" data-bs-target="#edit_holidays">
                                                <i class="bx bx-edit"></i>
                                            </a> 
                                    </td>
                                        <td>
                                            <a href="#" id="' . $holiday -> id . '" type="button" class="btn-view1 text-danger btn-sm mx-1 holiday_delete_icon" data-bs-toggle="modal" data-bs-target="#holiday_delete">
                                                <i class="bx bx-trash"></i>
                                            </a>
                                    </td>';
                    }

                   
             $output.='</tr> ';
			}

			$output .= '</tbody></table></div>';
			echo $output;

        }
        else {

            echo '<h1 class="text-center text-secondary my-5">No record present in the database!</h1>';
        }
    }
    public function edit_holiday(Request $request){

		$edit_holiday = Holiday::find($request -> id);

		return response()->json($edit_holiday);
    }
    public function update_holiday(Request $request){

        $validator = \Validator::make($request -> all(),[

            'holiday_name' =>   'required',
            'holiday_date' =>   'required',
            'holiday_type' =>   'required',
        ]);

        if($validator -> fails()){

            return response()->json([
                'code' => 0,
                'error' => $validator->errors()->toArray()
            ]);
        }
        else{
            
            $update_holiday = Holiday::find($request->holiday_id);;
            $update_holiday -> holiday_name = $request -> holiday_name;
            $update_holiday -> holiday_date = $request -> holiday_date;
            $update_holiday -> holiday_type = $request -> holiday_type;
            $update_holiday -> update();

            return response()->json([

                'status' => 200,
                'msg' => 'Holiday Updated Successfully',
		    ]);


        }
    }
    public function delete_holiday(Request $request){

		$delete_holiday = Holiday::find($request->id);
        Holiday::destroy($request->id);

    }
}
