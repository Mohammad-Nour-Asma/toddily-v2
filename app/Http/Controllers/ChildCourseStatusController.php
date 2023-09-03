<?php

namespace App\Http\Controllers;

use App\Models\ChildCourseStatus;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ChildCourseStatusController extends Controller
{
    //
    public function store(Request $request){
        $fields =  $request->validate([
            'child_course_id'=> 'required|numeric',
            'description'=> 'required|string',
        ]);


        $course = ChildCourseStatus::create(
            $fields
        );


        return response(['courseStatus' =>$course ,'message' => 'created successfully'], 200);
    }
    public function destroy(string $id)
    {
        //
        $course = ChildCourseStatus::find($id);
        if(!$course){
            return response(['message'=>'not Found'],400);
        }


        $course->delete();

        return response(['message'=>'deleted Successfully'],200);

    }
}
