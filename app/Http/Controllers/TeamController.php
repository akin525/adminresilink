<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    function fetchteams(Request $request){
        $data=Team::paginate(15);
        return response()->json(['status'=>'true','message'=>'Team Fetched','data'=>$data
        ],200);
    }
    function fetchteamsbyid($id){

        $data=Team::whereId($id)->first();
        if (!isset($data)){
            return response()->json(['status'=>'false','message'=>'No Team with the id'],200);
        }
        return response()->json(['status'=>'true','message'=>'Team Fetched','data'=>$data
        ],200);
    }
}
