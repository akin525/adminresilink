<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{

    function dashboard()
    {
        $data['users'] = User::count();
        $data['property'] = Property::count();
        $data['property_sell'] = Property::where('mode', 'SALE')->count();
        $data['property_rent'] = Property::where('mode', 'RENT')->count();
        $data['tenant'] = Tenant::count();
        $data['revenue'] = Tenant::sum('amount');
        $data['recent_property'] = Property::orderBy('id', 'desc')
            ->limit(10)
            ->get();
        ;
        // Get monthly stats
        $monthlyStats = Tenant::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(amount) as total_revenue'),
            DB::raw('COUNT(id) as total_sales')
        )
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get()
            ->keyBy('month');

        $monthlyRevenue = [];
        $monthlySales = [];

        foreach (range(1, 12) as $month) {
            $monthlyRevenue[] = $monthlyStats[$month]->total_revenue ?? 0;
            $monthlySales[] = $monthlyStats[$month]->total_sales ?? 0;
        }

        $data['monthlyRevenue'] = $monthlyRevenue;
        $data['monthlySales'] = $monthlySales;

        return view('dashboard', compact('data'));
    }
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
            'country' => 'required',
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
            'country' => $request->country,
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
    }}
