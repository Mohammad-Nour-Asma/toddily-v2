<?php

namespace App\Http\Controllers;

use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ChildrenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $posts = Child::all();
        return response(['children' =>$posts] , 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $fields = $request->validate([
            'name'=>'string|required',
            'parent_id'=>'numeric|required',
            'image' => 'image|required',
            'classRoom_id' => 'numeric|required',
            'isExtra' => 'boolean|required',
        ]);

        if($request->hasFile('image')){
            $image = $request->file('image');
            $filename = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $filename);
            $fields['image'] = '/images/'.$filename;
        }

        $child = Child::create($fields);
        return response([
           'child'=> $child,
        ],200);

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
        //

        $fields = $request->validate([
            'name'=>'string',
            'parent_id'=>'numeric',
            'image' => 'image',
            'classRoom_id' => 'numeric'
        ]);


        $child = Child::find($id);


        if(!$child){
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
        $imagePath = public_path($child->image);

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $child->update($fields);
        return response([
            'post' => Child::find($id),
            'message'=> 'Updated Successfully',

        ],200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $child = Child::find($id);

        if (!$child) {
            return response()->json(['message' => 'child not found'], 404);
        }

        // Delete the image file from the storage
        $imagePath = public_path($child->image);

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Delete the image record from the database
        $child->delete();
        return response()->json(['message' => 'Child deleted successfully']);
    }

    public function getStatusChildren(string $id){
        $child = Child::find($id);

        if (!$child) {
            return response()->json(['message' => 'Image not found'], 404);
        }
        return response(['child_status' =>$child->classRoom->ageSection->status]);
    }

    public function getChildrenStatusDates(string $id){
        $child = Child::find($id);

        if (!$child) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        return response([$child->status]);
    }
}
