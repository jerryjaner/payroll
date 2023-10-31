<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;



class UserController extends Controller
{   
   
    public function index()
    {    
        if(Auth::user()->hasRole(['administrator','HR','assistantHR','CEO'])){

            return view('users.index');
        }
        else{

          return redirect()->back();
        }
       
        
    }
    
    public function fetch_user(){

        $users = User::all();

        $output = '';
		if ($users->count() > 0)
        {
			$output .= '<table class="employee-tbl table" style="width: 100%" id="user_table">
            <thead>
              <tr>
                 <th hidden> </th>
              </tr>
            </thead>
            <tbody>';
			foreach ($users as $user) {
				$output .= ' <tr>
                    <td>
                        <div class="card time-card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-1" >
                                        <div class="d-flex">

                                            <p class="user_id" hidden>'.$user -> id.'</p>';

                                            if($user -> profile_image != null){
                                                
                                                $output .=  '<img src="storage/user/images/' . $user -> profile_image . '" style="border-radius: 100%; border: 0.5px solid gray;  padding: 1px; width:50px; height: 50px;">';
                                            }
                                            else{

                                                $output .= '<i class="bx bx-user icon profile-picture-time"> </i>';
                                            }
                        $output .= ' </div>
                                        </div>

                                            <div class="col-xl-3">
                                                <h5 class="name">'.$user -> name .'</h5>
                                                <p class="emp-no"> Name</p>
                                            </div>
                                            <div class="col-xl-2">
                                                <h5 class="name">'.$user -> department .'</h5>
                                                <p class="emp-no"> Department</p>
                                            </div>
                                            <div class="col-xl-2">
                                                <h5 class="name">'.$user -> position .'</h5>
                                                <p class="emp-no"> Position</p>
                                            </div>
                                            <div class="col-xl-2">
                                                <h5 class="name">';
                                                    if($user -> role == 'administrator'){
                                                        
                                                        $output .= 'Admin';

                                                    }
                                                    else if($user -> role == 'HR'){
                                                        
                                                        $output .= 'HR';
                                                    }
                                                    else if($user -> role == 'accounting'){
                                                        
                                                        $output .= 'Accounting';
                                                    }
                                                    else if($user -> role == 'employee'){
                                                        
                                                        $output .= 'Employee';
                                                    }
                                                    else if($user -> role == 'attendance'){
                                                        
                                                        $output .= 'Attendance';
                                                    }
                                                    else if($user -> role == 'manager'){
                                                        
                                                        $output .= 'Manager';
                                            
                                                    }
                                                    else if($user -> role == 'COO'){
                                                        
                                                        $output .= 'Chief Operaing Officer';
                                            
                                                    }
                                                    else if($user -> role == 'VPO'){
                                                        
                                                        $output .= 'Vice President For Operation';
                                            
                                                    }
                                                    else if($user -> role == 'CEO'){
                                                        
                                                        $output .= 'CEO / President';
                                            
                                                    }
                                                    else if($user -> role == 'SVPT'){
                                                        
                                                        $output .= 'Senior Vice President For Technology';
                                            
                                                    }
                                                    else if($user -> role == 'legal'){
                                                        
                                                        $output .= 'Legal / Account Manager';
                                            
                                                    }
                                                    else if($user -> role == 'assistantHR'){
                                                        
                                                        $output .= 'Assistant HR Manager';
                                            
                                                    }
                                                    else{
                                                        
                                                        $output .= 'Team Leader';
                                                    }
                                                    $output .= '</h5>
                                                <p class="emp-no"> Role</p>
                                            </div>';

                                 
                                    if(Auth::user()->hasRole(['HR','assistantHR'])){
                                        $output.='  <div class="col-xl-2 d-flex justify-content-end">
                                            <a href="#" type="button" id="' .$user -> id.'" class="btn-view editPassword" data-tippy-content="Change Password" data-tippy-arrow="false" data-bs-toggle="modal" data-bs-target="#ChangePassword">
                                                <i class="bx bx-key"></i>
                                            </a>
                                            <a href="#" type="button" id="' .$user -> id.'" class="btn-view editIcon" data-tippy-content="View Profile" data-tippy-arrow="false" data-bs-toggle="modal" data-bs-target="#EditUser">
                                                <i class="bx bx-edit"></i>
                                            </a>';
                                    }

                                      

                                        // <a href="#" type="button" id="' .$user -> id.'" class="btn-view deleteIcon" data-tippy-content="Delete Profile" data-tippy-arrow="false" data-bs-toggle="modal" data-bs-target="#DeleteUser">
                                        //     <i class="bx bx-trash"></i>
                                        // </a>

                            $output .='</div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>';
			}
			$output .= '</tbody></table>';
			echo $output;
		} 
        else 
        {
			echo '<h1 class="text-center text-secondary my-5">No record present in the database!</h1>';
		}                 
    }

    //edit user modal
    public function edit_user(Request $request) 
    {
		$id = $request->id;
		$user = User::find($id);
		return response()->json($user);
	}

    //update user modal
    public function update_user(Request $request) 
    {
        //To validate the user
        $validator = \Validator::make($request -> all(),[
           
            'username' => ['required', Rule::unique('users')->ignore($request->user_id) ],
            'name' => 'required', 
            'role' => 'required',
            'email' => 'required',
        ]);

        if($validator -> fails())
        {
            return response()->json([
                'code' => 0, 
                'error' => $validator->errors()->toArray()
            ]);
        }
        else
        {
            $user_update = User::find($request->user_id);
            $fileName = '';

            if($request ->hasfile('profile_image'))
            {
                $file = $request->file('profile_image');
                $extension = $file -> getClientOriginalExtension();
                $fileName = time() . '.' .$extension;
                $file->storeAs('public/user/images', $fileName);
                if ($user_update->profile_image)
                {
                    Storage::delete('public/user/images/' . $user_update -> profile_image);
                }
                $user_update -> profile_image = $fileName;
            }
                //$user_update -> name = $request -> name;
                $user_update -> email = $request -> email;
                $user_update -> username = $request -> username;
               // $user_update -> position = $request -> position;
                $user_update -> role = $request -> role;
                
                $user_update -> update();               
                
                 $user_update->detachRole($request -> id); //remove role
                 $user_update->attachRole($request -> role);
               

            return response()->json([
                'status' => 200,
                'msg' => 'User Update Successfully',
            ]);
            
        }
	}

    //handle delete an user ajax request
	public function delete_user(Request $request) {
		$id = $request->id;
		$userss = User::find($id);

        if (Storage::delete('public/user/images/' . $userss->profile_image)) {

			User::destroy($id);
		}
        else{
            
            User::destroy($id);
        }
	}

    //view change password
    public function viewpassword(Request $request) 
    {
		$idpass = $request->id;
		$user1 = User::find($idpass);
		return response()->json($user1);
	}

    //update password
    public function update_password(Request $request)
    {
        $validator = \Validator::make($request -> all(),[
            'new_password' => 'required|min:8|max:20',
            'confirm_password' => 'required|same:new_password',
        ]);

        if($validator -> fails())
        {
            return response()->json([
                'code' => 0, 
                'error' => $validator->errors()->toArray()
            ]);
        }
        else
        {

            $user_password = User::find($request -> usern_id);
            $user_password->password = Hash::make($request-> new_password);
            $user_password->update();
                
            return response()->json([
            'status' => 200,
            'msg' => 'Password Update Successfully',
            ]);
        }
    }
}