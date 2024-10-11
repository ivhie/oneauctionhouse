<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function index() {
       
       // $user = auth()->user();
        //if ($user) {
          $page = array(
              'menu'=>'dashboard'
          );
            return view('admin.dashboard')->with('page',$page);
        //} else {
        //    return redirect('/');

      //  }
       
       
    }



}
