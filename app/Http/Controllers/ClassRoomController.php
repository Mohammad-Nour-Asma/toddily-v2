<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Symfony\Component\Console\Input\Input;

class ClassRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $respose = ClassRoom::with(['children' , 'teacher'])->get();
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
            'age_section_id'=> 'required|numeric',
            'teacher_id'=> ['numeric','required' , Rule::exists('users' , 'id')]

        ]);

        $class = ClassRoom::create($fields);
        return response(['class'=>$class,
            'message'=>'created successfully'
        ] ,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //

        return response(['class'=>ClassRoom::with('children')->find($id)],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        //
        $classRoom = ClassRoom::find($id);
        if(!$classRoom)
        {
            return response(['message'=>'Classroom not found'],404 );

        }

        $fields =  $request->validate([
            'name' => 'string',
            'age_section_id'=> 'numeric',
            'teacher_id' => 'numeric'
        ]);

        $classRoom->update($fields);
        return response(['message'=>'updated successfully' ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $classRoom = ClassRoom::find($id);
        if(!$classRoom)
        {
            return response(['message'=>'class room not found'],404 );

        }
        $classRoom->delete();

        return response(['message'=>'deleted Successfully'],200 );
    }


}
