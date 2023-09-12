<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CourseController extends Controller
{
    //
    public function index(){
        return response([
            'courses' => Course::all()
        ]);
    }

    public function store(Request $request){
        $fields =  $request->validate([
            'name'=> 'required|string',
            'description'=> 'required|string',
            'image'=>'image|required',
        ]);

        if($request->hasFile('image')){
            $image = $request->file('image');
            $filename = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $filename);
            $fields['image'] = '/images/'.$filename;
        }
        $course = Course::create(
            $fields
        );


        return response(['course' =>$course ,'message' => 'created successfully'], 200);
    }

    public function update(Request $request, string $id)
    {
        //


        $fields = $request->validate([
            'name'=> 'string',
            'description'=> 'string',
            'image'=>'image|',
        ]);


        $course = Course::find($id);


        if(!$course){
            return response(['message'=>'not Found'],400);
        }



        if($request->hasFile('image')){
            $image = $request->file('image');
            $filename = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $filename);
            $fields['image'] = '/images/'.$filename;
        }

        // Delete The old Image
        $imagePath = public_path($course->image);

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $course->update($fields);
        return response([
            'course' => $course,
            'message'=> 'Updated Successfully',

        ],200);
    }


    public function destroy(string $id)
    {
        //
        $course = Course::find($id);
        if(!$course){
            return response(['message'=>'not Found'],400);
        }

        $imagePath = public_path($course->image);

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $course->delete();

        return response(['message'=>'deleted Successfully'],200);

    }

    public function show(string $id)
    {
        //
        $course = Course::find($id);
        if(!$course){
            return response(['message'=>'not Found'],400);
        }

        $children = $course->children->map(function ($item){
            return [
                'id'=> $item->child->id,
                'name'=>$item->child->name,
                'isExtra'=>$item->child->isExtra,
                'image'=>$item->child->image,
            ];
        });

            $courseInfo = [
                'id'=>$course->id,
                'name'=>$course->name,
                'image'=>$course->image,
                'children'=>$children
            ];

        return response(['course'=>$courseInfo ],200);
    }

}
