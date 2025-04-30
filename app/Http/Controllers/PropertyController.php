<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PropertyController extends Controller
{

    function property()
    {
        $data=Property::paginate(15);
        return view('property',['data'=>$data]);
    }

    function addpro()
    {

        return view('addproperty');
    }
    public function addproperty(Request $request)
    {
        $request->validate([
            'tittle' => 'required',
            'type' => 'required|in:SINGLE_ROOM,SELF_CONTAINER,FLAT,DUB',
            'price' => 'required|numeric',
            'address' => 'required',
            'mode' => 'required|in:RENT,SALE',
            'commission' => 'required|numeric',
            'rooms' => 'required|numeric',
            'state' => 'required',
            'description' => 'required',
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'video' => 'nullable|mimes:mp4,mov,avi|max:10240', // optional
        ]);

        $imagePaths = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $filename);
                $imagePaths[] = 'uploads/' . $filename;
            }
        }

        $videoPath = null;
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $videoFilename = 'video_' . time() . '.' . $video->getClientOriginalExtension();
            $video->move(public_path('uploads'), $videoFilename);
            $videoPath = 'uploads/' . $videoFilename;
        }

        // Calculate total price
        $totalPrice = $request->price;
        if ($request->mode == 'RENT') {
            $totalPrice += $request->commission;
        }

        Property::create([
            'title' => $request->tittle,
            'type' => $request->type,
            'mode' => $request->mode,
            'status' => 'ACTIVE',
            'price' => $request->price,
            'commission' => $request->commission,
            'total_price' => $totalPrice,
            'rooms' => $request->rooms,
            'address' => $request->address,
            'state' => $request->state,
            'description' => $request->description,
            'images' => json_encode($imagePaths),
            'video' => $videoPath,
            'posted_by' => auth()->id(),
        ]);

        toast('Property added successfully', 'success');
        return back();
    }

    function editproperty($id)
    {
        $data=Property::where('id',$id)->first();
        return view('editproperty',['data'=>$data]);
    }
    function updateproperty(Request $request, $id)
    {
        $request->validate([
            'tittle' => 'required',
            'type' => 'required|in:SINGLE_ROOM,SELE_CONTAINER,FLAT,DUB',
            'price' => 'required|numeric',
            'address' => 'required',
            'mode' => 'required|in:RENT,SALE',
            'commission' => 'required|numeric',
            'rooms' => 'required|numeric',
            'city' => 'required',
            'state' => 'required',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Removed 'required' from nullable
            'video' => 'nullable|mimes:mp4,mov,avi|max:10000' // Added video validation
        ]);

        // Find the property to update
        $property = Property::findOrFail($id);

        $updateData = [
            'tittle' => $request->tittle,
            'type' => $request->type,
            'mode' => $request->mode,
            'price' => $request->price,
            'commission' => $request->commission,
            'total_price' => $request->mode == 'RENT' ? $request->price + $request->commission : $request->price,
            'rooms' => $request->rooms,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'description' => $request->description,
            'updated_at' => now()
        ];

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('uploads/', $filename);
            $updateData['file'] = 'uploads/' . $filename;

            // Update images array (append new image or replace)
            $currentImages = json_decode($property->images, true) ?? [];
            $updateData['images'] = json_encode(array_merge($currentImages, ['uploads/' . $filename]));
        }

        // Handle video upload if present
        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $extension = $file->getClientOriginalExtension();
            $filename = 'video_' . time() . '.' . $extension;
            $file->move('uploads/', $filename);
            $updateData['video'] = 'uploads/' . $filename;
        }

        // Update the property
        $property->update($updateData);

        return redirect()->back()->with('success', 'Property updated successfully');
    }

    function fetchproperties(Request $request){
        $data=Property::paginate(15);
        return response()->json(['status'=>'true','message'=>'Property Fetched','data'=>$data
        ],200);
    }
    function fetchpropertiesbyid($id){

        $data=Property::whereId($id)->first();
        if (!isset($data)){
            return response()->json(['status'=>'false','message'=>'No Property with the id'],200);
        }
        return response()->json(['status'=>'true','message'=>'Property Fetched','data'=>$data
        ],200);
    }


    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'title' => 'required',
                'type' => 'required',
                'mode' => 'required',
                'status' => 'required',
                'price' => 'required',
                'commission' => 'required',
                'total_price' => 'required',
                'rooms' => 'required',
                'address' => 'required',
                'state' => 'required',
                'description' => 'required',
                'images' => 'required|array', // Ensure it's an array
                'posted_by' => 'required',
            ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'false',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Find user by email
        $user = User::whereId($request->posted_by)->first();

        if (!$user) {
            return response()->json(['status' => 'false',
                'message' => 'Invalid user'], 401);
        }

        if ($user->type !== "users") {
            return response()->json(['status' => 'false',
                'message' => 'You are not allowed to upload a property. Kindly become an Agent'], 401);
        }

        // Convert images to JSON string if it's an array
        $images = is_array($request->images) ? json_encode($request->images) : $request->images;

        // Convert video to string if it's an array or null
//        $video = $request->video ? (is_array($request->video) ? json_encode($request->video) : $request->video) : null;

        // Create property
        $property = Property::create([
            'title' => $request->title,
            'type' => $request->type,
            'mode' => $request->mode,
            'status' => $request->status,
            'price' => $request->price,
            'commission' => $request->commission,
            'total_price' => $request->total_price,
            'rooms' => $request->rooms,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'description' => $request->description,
            'images' => $images,
//            'video' => $video,
            'posted_by' => $request->posted_by,
        ]);

        return response()->json([
            'status' => 'true',
            'message' => 'Property uploaded successfully.',
            'data' => $property
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'property_id' => 'required',
            ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'false',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Find user by email
        $user = User::whereId($request->posted_by)->first();

        if (!$user) {
            return response()->json([
                'status' => 'false',
                'message' => 'Invalid user'], 401);
        }

        if (!$user->type == "users") {
            return response()->json(['status' => 'false',
                'message' =>  'You are not allowed to upload a property. Kindly become an Angent'], 401);
        }

        $property = Property::whereId($request->property_id)->first();
        if (!$property) {
            return response()->json([
                'status' => 'false',
                'message' => 'Invalid property'], 401);
        }
        // update property
        $property->title = $request->title;
        $property->type = $request->type;
        $property->mode = $request->mode;
        $property->status = $request->status;
        $property->price = $request->price;
        $property->commission = $request->commission;
        $property->total_price = $request->total_price;
        $property->rooms = $request->rooms;
        $property->address = $request->address;
        $property->city = $request->city;
        $property->state = $request->state;
        $property->country = $request->country;
        $property->description = $request->description;
        $property->images = $request->images;
        $property->video = $request->video;
        $property->posted_by = $request->posted_by;

        return response()->json([
            'status'=>'true',
            'message'=>'Property Updated successful.',
            'data' => $property
        ]);
    }
}
