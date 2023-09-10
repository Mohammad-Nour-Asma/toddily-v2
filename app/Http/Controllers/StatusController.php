<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $respose = Status::with('substatus')->get();
        return response($respose , 202);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $fields =  $request->validate([
            'name' => 'required|string',
            'ageSection_id'=> 'required|numeric'
        ]);

        $status = Status::create($fields);
        return response(['status'=>$status,
            'message'=>'created successfully'
        ] ,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $status = Status::find($id);
        if(!$status)
        {
            return response(['message'=>'Status not found'],404 );
        }
        $status->substatus;
        return  response([
            "status"=> $status,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $status = Status::find($id);
        if(!$status)
        {
            return response(['message'=>'Status not found'],404 );

        }
        $fields =  $request->validate([
            'name' => 'string',
            'ageSection_id'=> 'numeric'
        ]);
           $status->update($fields);

        return response(['message'=>'updated successfully' ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $status = Status::find($id);
        if(!$status)
        {
            return response(['message'=>'Status not found'],404 );

        }
        $status->delete();


        return response(['message'=>'deleted Successfully'],200 );
    }


}
