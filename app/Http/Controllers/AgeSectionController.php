<?php

namespace App\Http\Controllers;

use App\Models\AgeSection;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AgeSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $response = AgeSection::with(['classRoom','status'])->get();
        return response([
            'data'=> $response

        ] , 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $fields =  $request -> validate([
            'from'=> 'required |numeric ',
            'to'=> 'required |numeric ',
        ]);

        $response = AgeSection::create($fields);

        return response([
            'response' => $response,
        ], 200);

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
        if( !($ageSection = AgeSection::find($id))){
            return response(['message'=>'Age Section Not Found'] , 400);
        }
        $fields =  $request -> validate([
            'from'=> 'numeric ',
            'to'=> 'numeric ',
        ]);

        $ageSection->update(
            $fields
        );

        return response([
            'response' => AgeSection::find($id),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $ageSection = AgeSection::find($id);
        if(!$ageSection)
        {
            return response(['message'=>'age section not found'],404 );

        }
        $ageSection->delete();


        return response(['message'=>'deleted Successfully'],200 );
    }
}
