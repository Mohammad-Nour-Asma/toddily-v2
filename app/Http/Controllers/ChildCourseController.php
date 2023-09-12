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
            'child_ids'=> 'required',
            'course_id'=> 'required|numeric',
        ]);




         $childIDS = json_decode($request->child_ids);
        foreach ($childIDS as $id){
            $childCourse = ChildCourse::where('child_id', $id)
                ->where('course_id', $request->course_id)
                ->first();
            if(!$childCourse) {
                $course = ChildCourse::create(
                    [
                        'child_id' => $id,
                        'course_id' => $request->course_id
                    ]
                );
            }
        }




        return response(['message' => 'created successfully'], 200);
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
