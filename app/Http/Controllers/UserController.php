<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    function fetchusers(Request $request){
        $data=User::Where('type','users')->paginate(15);
        return response()->json(['status'=>'true','message'=>'Users Fetched','data'=>$data
        ],200);
    }
     function fetchagent(Request $request){
        $data=User::Where('type','agents')->paginate(15);
        return response()->json(['status'=>'true','message'=>'Agents Fetched','data'=>$data
        ],200);
    }
    function fetchuserbyid($id){

        $data=User::whereId($id)->first();
        if (!isset($data)){
            return response()->json(['status'=>'false','message'=>'No User with the id'],200);
        }
        return response()->json(['status'=>'true','message'=>'Team Fetched','data'=>$data
        ],200);
    }

    function alluser()
    {
        $data=User::all();
        return view('users',compact('data'));

    }
    function edituser($id)
    {
        $data=User::where('id',$id)->first();
        if (!isset($data)){
            toast('User Not Found','error','top-right');
            return back();
        }
        return view('edituser',compact('data'));
    }
    function updateuser(Request $request,$id)
    {
        $data=User::where('id',$id)->first();
        if (!isset($data)){
            toast('User Not Found','error','top-right');
            return back();
        }
    $update=$request->validate([
            'name'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'address'=>'required',
            'type'=>'required',
            'status'=>'required',
        ]);
        User::update($update);
        toast('User Updated Successfully','success','top-right');
        return back();
    }
}
