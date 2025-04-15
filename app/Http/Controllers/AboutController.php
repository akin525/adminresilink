<?php

namespace App\Http\Controllers;

use App\Models\Aboutus;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    function fetchaboutus(Request $request){
        $data=Aboutus::first();
        return response()->json(['status'=>'true','message'=>'About Us Fetched','data'=>$data
        ],200);
    }
}
