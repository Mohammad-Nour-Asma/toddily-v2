<?php

namespace App\Http\Controllers;

use App\Models\ChildCourse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ChildCourseController extends Controller
{
    //
    public function store(Request $request){
        $fields =  $request->validate([
            'child_id'=> 'required|numeric',
            'course_id'=> 'required|numeric',
        ]);


        $course = ChildCourse::create(
            $fields
        );


        return response(['course' =>$course ,'message' => 'created successfully'], 200);
    }
    public function destroy(string $id)
    {
        //
        $course = ChildCourse::find($id);
        if(!$course){
            return response(['message'=>'not Found'],400);
        }


        $course->delete();

        return response(['message'=>'deleted Successfully'],200);

    }
}
