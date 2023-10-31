<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\SSS_deduction;
use App\Models\Pagibig_deduction;
use App\Models\Philhealth_deduction;
use Illuminate\Support\Facades\Auth;

class DeductionController extends Controller
{
    public function index(){

        return view('deduction.index');
    }

    // SSS
    public function add_sss_deduction(Request $request){

        $validator = \Validator::make($request -> all(),[

            'range_from' => 'required|numeric|min:0|max:10000000',
            'range_to' => 'required|numeric|min:0|max:10000000',
            'regular_ec' =>'required|numeric|min:0|max:10000000',
            'wisp' => 'required|numeric|min:0|max:10000000',
            'regular_er' => 'required|numeric|min:0|max:10000000',
            'regular_ee' => 'required|numeric|min:0|max:10000000',
            'ecc' => 'required|numeric|min:0|max:10000000',
            'wisp_er' => 'required|numeric|min:0|max:10000000',
            'wisp_ee' => 'required|numeric|min:0|max:10000000',
        ]);

        if($validator -> fails()){

            return response()->json([
                'code' => 0,
                'error' => $validator->errors()->toArray()
            ]);
        }
        else{

            $add_sss = new SSS_deduction();
            $add_sss -> from = $request -> range_from;
            $add_sss -> to = $request -> range_to;
            $add_sss -> regular_ec = $request -> regular_ec;
            $add_sss -> wisp = $request -> wisp;
            $add_sss -> regular_ER = $request -> regular_er;
            $add_sss -> regular_EE = $request -> regular_ee;
            $add_sss -> ECC = $request -> ecc;
            $add_sss -> wisp_ER = $request -> wisp_er;
            $add_sss -> wisp_EE = $request -> wisp_ee;
            $add_sss -> save();

            return response()->json([

                'status' => 200,
                'msg' => 'SSS Deduction Added Successfully',
		    ]);


        }
    }

    public function sss_deduction(Request $request){
        $sss_data = SSS_deduction::all();

        $output = '';
        if ($sss_data->count() > 0) {

            $output .= '<table class="table table-striped table-hover border border-gray" style="width: 100%; height:auto;" id="sss_table">
            <thead>
                <tr style="font-size: 1.1rem;">
                   <th colspan="2" class="text-center border border-gray">  Range of Compensation  </th>
                   <th colspan="2"  class="text-center border border-gray">  Monthly Salary Credit   </th>
                   <th colspan="2"  class="text-center border border-gray">Regular</th>
                   <th class="text-center border border-gray">EC</th>
                   <th colspan="2"  class="text-center border border-gray">WISP</th>';

                    if(Auth::user()->hasRole('accounting')){
                        $output.='<th colspan="2"  class="text-center border border-gray">Actions</th>';
                    }
     $output.=' </tr>
                <tr style="font-size: 0.9rem;">
                    <th  class="text-center border border-gray">From</th>
                    <th  class="text-center border border-gray">To</th>
                    <th  class="text-center border border-gray">Regular / EC</th>
                    <th  class="text-center border border-gray">WISP</th>
                    <th  class="text-center border border-gray">ER</th>
                    <th  class="text-center border border-gray">EE</th>
                    <th  class="text-center border border-gray">ECC</th>
                    <th  class="text-center border border-gray">ER</th>
                    <th  class="text-center border border-gray">EE</th>';
                    if(Auth::user()->hasRole('accounting')){
                        $output.=' <th  class="text-center border border-gray">Edit</th>
                                   <th  class="text-center border border-gray">Delete</th>';
                    }

                   
                $output.='</tr>
            </thead>
            <tbody>';
            foreach ($sss_data as $sss) {
                $output .=
                '<tr style="font-size: 1rem; vertical-align:middle;">

                    <td>
                        <h5 class="time text-center">'.$sss -> from. '</h5>
                    </td>
                    <td>
                        <h5 class="name text-center">'. $sss -> to.'</h5>
                    </td>
                    <td>
                        <h5 class="name text-center">'.$sss -> regular_ec.'</h5>
                    </td>
                    <td>
                        <h5 class="name text-center">'. $sss -> wisp.'</h5>
                    </td>
                    <td>
                       <h5 class="name text-center">'.$sss -> regular_ER.'</h5>
                    </td>
                    <td>
                        <h5 class="name text-center">'. $sss -> regular_EE.'</h5>
                    </td>
                    <td>
                        <h5 class="name text-center">'. $sss -> ECC.'</h5>
                    </td>
                    <td>
                        <h5 class="name text-center">'. $sss -> wisp_ER.'</h5>
                    </td>
                    <td>
                        <h5 class="name text-center">'. $sss -> wisp_EE.'</h5>
                    </td>';
                    if(Auth::user()->hasRole('accounting')){
                        $output.='  <td>
                                            <a href="#" id="' . $sss -> id . '" type="button" class="btn-view1 text-success btn-sm mx-1 sss_edit_icon" data-bs-toggle="modal" data-bs-target="#sss_edit">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                    </td>
                                    <td>
                                        
                                        <a href="#" id="' . $sss -> id . '" type="button" class="btn-view1 text-danger btn-sm mx-1 sss_delete_icon" data-bs-toggle="modal" data-bs-target="#sss_delete">
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

    public function edit_sss(Request $request){

		$edit_sss = SSS_deduction::find($request -> id);

		return response()->json($edit_sss);
    }

    public function update_sss(Request $request){

        $validator = \Validator::make($request -> all(),[

            'range_from' => 'required',
            'range_to' => 'required',
            'regular_ec' => 'required',
            'wisp' => 'required',
            'regular_er' => 'required',
            'regular_ee' => 'required',
            'ecc' => 'required',
            'wisp_er' => 'required',
            'wisp_ee' => 'required',
        ]);

        if($validator -> fails()){

            return response()->json([
                'code' => 0,
                'error' => $validator->errors()->toArray()
            ]);
        }
        else{

            $update_sss = SSS_deduction::find($request->sss_id);
            $update_sss -> from = $request -> range_from;
            $update_sss -> to = $request -> range_to;
            $update_sss -> regular_ec = $request -> regular_ec;
            $update_sss -> wisp = $request -> wisp;
            $update_sss -> regular_ER = $request -> regular_er;
            $update_sss -> regular_EE = $request -> regular_ee;
            $update_sss -> ECC = $request -> ecc;
            $update_sss -> wisp_ER = $request -> wisp_er;
            $update_sss -> wisp_EE = $request -> wisp_ee;

            $update_sss -> update();

            return response()->json([

                'status' => 200,
                'msg' => 'SSS Deduction Updated Successfully',
		    ]);


        }
    }

    public function delete_sss(Request $request){

		$delete_sss = SSS_deduction::find($request->id);
        SSS_deduction::destroy($request->id);

    }

    // Pag-ibig
    public function pagibig_deduction(Request $request){
        $pagibig_data = Pagibig_deduction::all();

        $output = '';
        if ($pagibig_data->count() > 0) {

            $output .= '<table class="pagibig-tbl table table-striped table-hover border border-gray" style="width: 100%" id="pagibig_table">
                <thead>
                    <tr>
                        <th class="text-center border border-gray">#</th>
                        <th colspan="2" class="text-center border border-gray ">Monthly Salary</th>
                        <th class="text-center border border-gray">Employees Share</th>
                        <th class="text-center border border-gray">Employers Share</th>';
                        if(Auth::user()->hasRole('accounting')){
                            $output.='<th colspan="2" class="text-center border border-gray">Action</th>';
                        }
                        
          $output.='</tr>
                        <th></th>
                        <th class="text-center border border-gray">From</th>
                        <th class="text-center border border-gray">To</th>
                        <th class="text-center border border-gray"></th>
                        <th class="text-center border border-gray"></th>';
                        if(Auth::user()->hasRole('accounting')){
                            $output.=' <th class="text-center border border-gray">Edit</th>
                                    <th class="text-center border border-gray">Delete</th>';
                        }
                   
         $output.=' </tr>
                </thead>
                <tbody>';
                foreach ($pagibig_data as $pagibig) {
                    $output .=
                    '<tr style="font-size: 1rem; vertical-align:middle;">
                        <td>
                            <h5 class="name text-center">'.$pagibig -> id.'</h5>
                        </td>
                        <td>
                            <h5 class="name text-center">'.$pagibig -> monthly_salary_from.'</h5>
                        </td>
                        <td>
                            <h5 class="name text-center">'.$pagibig -> monthly_salary_to.'</h5>
                        </td>
                        <td>
                            <h5 class="name text-center">'.$pagibig -> employees_share.'</h5>
                        </td>
                        <td>
                            <h5 class="name text-center">'.$pagibig -> employer_share.'</h5>
                        </td>';

                            if(Auth::user()->hasRole('accounting')){
                                $output.=' <td>
                                                <a href="#" id="' . $pagibig -> id . '" type="button" class="btn-view1 btn-sm text-center text-success mx-1 pagibig_edit_icon" data-bs-toggle="modal" data-bs-target="#pagibig_edit">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="#" id="' . $pagibig -> id . '" type="button" class="btn-view1 btn-sm text-center text-danger mx-1 pagibig_delete_icon" data-bs-toggle="modal" data-bs-target="#pagibig_delete">
                                                    <i class="bx bx-trash"></i>
                                                </a>
                                            </td>';
                            }
                       
          $output.='</tr>';
			    }

			$output .= '</tbody></table>';
			echo $output;

        }
        else {
            echo '<h1 class="text-center text-secondary my-5">No record present in the database!</h1>';
        }

    }

    public function submit_pagibig(Request $request){
        $validator = \Validator::make($request -> all(),[

            'monthly_salary_from'       => 'required',
            'monthly_salary_to'         => 'required',
            'employees_share'           => 'required',
            'employer_share'            => 'required',
        ]);
        if($validator -> fails()){
            return response()->json([
                'code' => 0,
                'error' => $validator->errors()->toArray()
            ]);
        }
        else{
            Pagibig_deduction::create([

            'monthly_salary_from'       => $request->monthly_salary_from,
            'monthly_salary_to'         => $request->monthly_salary_to,
            'employees_share'           => $request->employees_share,
            'employer_share'            => $request->employer_share,

            ]);
            return response()->json([
                'code' => 200,
                'msg' => 'Pag-ibig Deduction Added Successfully',
            ]);

        }

    }

    public function edit_pagibig(Request $request){
        $id = $request->id;
		$pagibig = Pagibig_deduction::find($id);
		return response()->json($pagibig);
    }

    public function update_pagibig(Request $request){
        $validator = \Validator::make($request -> all(),[

            'monthly_salary_from'       => 'required',
            'monthly_salary_to'         => 'required',
            'employees_share'           => 'required',
            'employer_share'            => 'required',
        ]);
        if($validator -> fails()){
            return response()->json([
                'code' => 0,
                'error' => $validator->errors()->toArray()
            ]);
        }
        else{

            $pagibig_update = Pagibig_deduction::find($request->id_deducation_philhealth);
            $pagibig_update -> monthly_salary_from  = $request->monthly_salary_from;
            $pagibig_update -> monthly_salary_to  = $request->monthly_salary_to;
            $pagibig_update -> employees_share      = $request->employees_share;
            $pagibig_update -> employer_share       = $request->employer_share;
            $pagibig_update -> update();


            return response()->json([
                'code' => 200,
                'msg' => 'Pag-ibig Deduction Update Successfully',
            ]);

        }
    }

    public function delete_pagibig(Request $request){

		$delete_pagibig = Pagibig_deduction::find($request->id);
        Pagibig_deduction::destroy($request->id);

    }

    // Philhealth
    public function philhealth_deduction(Request $request){
        $philhealth_data = Philhealth_deduction::all();

        $output = '';
        if ($philhealth_data->count() > 0) {




            $output .= '<table class="table  table-striped table-hover border border-gray" style="width: 100%;" id="philhealth_table">
            <thead>
                <tr>
                   <th  class="text-center border border-gray">#</th>
                   <th colspan="2" class="text-center border border-gray">   Monthly Basic Salary   </th>
                   <th class="text-center border border-gray">  Premium Rate   </th>
                   <th class="text-center border border-gray">Monthly Premium</th>';
                   if(Auth::user()->hasRole('accounting')){
                    $output.=' <th colspan="2" class="text-center border border-gray">   Action   </th>';
                   }
                  
      $output.='</tr>
                <tr>
                    <th class="text-center border border-gray"></th>
                    <th class="text-center border border-gray">From</th>
                    <th class="text-center border border-gray">To</th>
                    <th class="text-center border border-gray"></th>
                    <th class="text-center border border-gray"></th>';
                    if(Auth::user()->hasRole('accounting')){
                        $output.='<th class="text-center border border-gray">Edit</th>
                        <th class="text-center border border-gray">Delete</th>';
                    }
                    
      $output.='</tr>
            </thead>
            <tbody>';
            foreach ($philhealth_data as $philhealth) {
                $output .=
                '<tr style="font-size: 1rem; vertical-align:middle;">
                    <td>
                        <h5 class="time text-center">'.$philhealth -> id. '</h5>
                    </td>
                    <td>
                        <h5 class="time text-center">'.$philhealth -> monthly_basic_salary_from. '</h5>
                    </td>
                    <td>
                        <h5 class="name text-center">'. $philhealth -> monthly_basic_salary_to.'</h5>
                    </td>
                    <td>
                        <h5 class="name text-center">'.$philhealth -> premium_rate.'</h5>
                    </td>
                    <td>
                        <h5 class="name text-center">'. $philhealth -> monthly_premium.'</h5>
                    </td>';

                    if(Auth::user()->hasRole('accounting')){
                         $output.='<td>
                                        <a href="#" id="' . $philhealth -> id . '" type="button" class="btn-view1 text-center text-success btn-sm mx-1 philhealth_edit_icon" " data-bs-toggle="modal" data-bs-target="#philhealth_edit">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#" id="' . $philhealth -> id . '" type="button" class="btn-view1 text-center text-danger btn-sm mx-1 philhealth_delete_icon" data-bs-toggle="modal" data-bs-target="#philhealth_delete">
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

    public function add_philhealth(Request $request){
        $validator = \Validator::make($request -> all(),[

            'monthly_basic_salary_from'     => 'required',
            'monthly_basic_salary_to'       => 'required',
            'premium_rate'                  => 'required',
            'monthly_premium'               => 'required',
        ]);
        if($validator -> fails()){
            return response()->json([
                'code' => 0,
                'error' => $validator->errors()->toArray()
            ]);
        }
        else{

            Philhealth_deduction::create([

                'monthly_basic_salary_from'     => $request->monthly_basic_salary_from,
                'monthly_basic_salary_to'       => $request->monthly_basic_salary_to,
                'premium_rate'                  => $request->premium_rate,
                'monthly_premium'               => $request->monthly_premium,

            ]);
            return response()->json([
                'code' => 200,
                'msg' => 'Philhealth Deduction Added Successfully',
            ]);

        }

    }

    public function edit_philhealth(Request $request){
		$edit_philhealth = Philhealth_deduction::find($request->id);
		return response()->json($edit_philhealth);
    }

    public function update_philhealth(Request $request){
        $validator = \Validator::make($request -> all(),[

            'monthly_basic_salary_from'     => 'required',
            'monthly_basic_salary_to'       => 'required',
            'premium_rate'                  => 'required',
            'monthly_premium'               => 'required',
        ]);
        if($validator -> fails()){
            return response()->json([
                'code' => 0,
                'error' => $validator->errors()->toArray()
            ]);
        }
        else{

            $philhealth_update = Philhealth_deduction::find($request->id_philhealth);
            $philhealth_update -> monthly_basic_salary_from     = $request->monthly_basic_salary_from;
            $philhealth_update -> monthly_basic_salary_to       = $request->monthly_basic_salary_to;
            $philhealth_update -> premium_rate                  = $request->premium_rate;
            $philhealth_update -> monthly_premium               = $request->monthly_premium;
            $philhealth_update -> update();

            return response()->json([
                'code' => 200,
                'msg' => 'Philhealth Deduction Added Successfully',
            ]);

        }
    }

    public function delete_philhealth(Request $request){

		$delete_philhealth = Philhealth_deduction::find($request->id);
        Philhealth_deduction::destroy($request->id);

    }
}
