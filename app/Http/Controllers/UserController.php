<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    //
    public function index(){
        $users = User::all();
        $page = array(
            'menu'=>'users',
            'users'=>$users
        );
        return view('admin.users')->with('page',$page);
    }

    public function store(Request $request) {
            //var_dump(request());
            /*$request->validate([
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
            ]);*/

            $validate = Validator::make($request->all(), [
                'name' => 'required|min:5',
                'email' => 'required|unique:users',
                'password' => 'required',
            ],[
                'name.required' => 'Required!',
                'name.min' => 'Min. Character is 5',
                'email.required' => 'Required!',
                'email.unique' => 'Email Already in use!',
                'password.required' => 'Required',
                
            ]);
            if($validate->fails()){
               return back()->withErrors($validate->errors())->withInput();
            }


            /*
            $this->validate(request(), [
                'uname' => 'required',
                'email' => 'required|email|unique:users',
                'password'=> 'required|min:6'
                //'password'=> 'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/'
            ]);
            */
            
            /*
            $company = Company::create([
                'name' => request()->companyName,
                'email' => request()->companyEmail,
                'telephone' => request()->companyTelephone,
                'address' => request()->companyAddress,
                'town' => request()->companyTown,
                'county' => request()->companyCounty,
                'postcode' => request()->companyPostcode
            ]);
            */
            /*
            $user = new User;
            $user->name = request()->name;
            $user->email = request()->email;
            $hash = Hash::make('password', [
                'rounds' => 12,
            ]);

            $user->password = $hash;
            $user->user_type = request()->user_type;
            var_dump($hash);
            var_dump(request()->password);
           
            var_dump($user->password);
            //var_dump( Hash::make($user->password));
            $user->save();
            */

            $user = User::create([
                'name' => request()->name,
                'email' => request()->email,
                'password' => bcrypt(request()->password)
            ]);
            //var_dump(request()->password);
            //var_dump(encrypt(request()->password));
            /**$user = User::create([
                'name' => request()->adminName, 
                'email' =>  request()->adminEmail, 
                'password' => encrypt( request()->adminPassword),
                'company_id' => $company->id
            ]);**/
    
          return redirect('/users')->with('status', 'User Succesfully Added!');
        //return view('user.show')->with('status', 'User Succesfully Added!');
            ///echo $this->index();
        }
}
