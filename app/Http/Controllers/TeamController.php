<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Team;
use App\Models\User;
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

    function viewteam()
    {
        $data=Team::all();
        return view('teams',['teams'=>$data]);

    }
    function addteam(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'position'=>'required',
            'image'=>'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $imageName = time().'.'.request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images'), $imageName);
        $create=Team::create([
            'name'=>$request->name,
            'position'=>$request->position,
            'image'=>$imageName,
        ]);
        if($create){
            toast('Team Created','success');
            return redirect()->back();
        }
        toast('Team Creation Failed','danger');
        return redirect()->back();
    }

    function editteam($id)
    {
        $data=Team::where('id',$id)->first();
        return view('editteam',['team'=>$data]);
    }
    function updateteam(Request $request,$id)
    {
        $data=Team::where('id',$id)->first();
        if (!$data){
            toast('Team not found', 'danger');
            return back();
        }
    }
}
