<?php

namespace App\Http\Controllers;

use App\Models\Substatus;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SubstatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $fields =  $request->validate([
            'name' => 'required|string',
            'status_id'=> 'required|numeric',
            'image'=> 'required|image'

        ]);

        if($request->hasFile('image')){
            $image = $request->file('image');
            $filename = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $filename);
            $fields['image'] = '/images/'.$filename;
        }

        $substatus = Substatus::create($fields);
        return response(['substatus'=>$substatus,
            'message'=>'created successfully'
        ] ,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $substatus = Substatus::find($id);
        if(!$substatus)
        {
            return response(['message'=>'Status not found'],404 );

        }
        $fields =  $request->validate([
            'name' => 'string',
            'description'=> 'string',
            'image'=> 'image'

        ]);

        if($request->hasFile('image')){
            $image = $request->file('image');
            $filename = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $filename);
            $fields['image'] = '/images/'.$filename;
        }

        // Delete The old Image
        $imagePath = public_path($substatus->image_cover);

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }


        $substatus->update($fields);
        $newSubstatus = Substatus::find($id);

        return response([
            'newSubstatus'=>$newSubstatus,
            'message'=>'updated successfully'
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $status = Substatus::find($id);
        if(!$status)
        {
            return response(['message'=>'substatus not found'],404 );

        }
        $status->delete();


        return response(['message'=>'deleted Successfully'],200 );
    }
}
