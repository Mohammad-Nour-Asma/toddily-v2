<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\ClassRoom;
use http\Client\Curl\User;
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
            'image' => 'image',
            'classRoom_id' => 'numeric|required',
            'isExtra' => 'boolean|required',
            'sex' => 'required|in:male,female',
        ]);

        if($request->hasFile('image')){
            $image = $request->file('image');
            $filename = uniqid().'.'.$image->getClientOriginalExtension();
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

        $child = Child::find($id);


        if(!$child){
            return response(['message'=>'not Found'],400);
        }


        $fields = $request->validate([
            'name'=>'string',
            'parent_id'=>'numeric',
            'image' => 'image',
            'classRoom_id' => 'numeric',
            'isExtra' => 'boolean|required',
            'sex' => 'required|in:male,female',

        ]);





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



    public  function getChildByRole(string $id){
        $user = \App\Models\User::find($id);

        if(!$user){
            return response(['message'=>'not found',404]);
        }
        $children = [];

        if($user->role->role_name == 'teacher' && $user->classRoom) {
            $cheldren = $user->classRoom->children;

//            $childrenWithClass = collect($cheldren)->map(function($item){
//                return []
//                });




        }else{
            $cheldren = $user->children;
        }

        $newChildren = $cheldren->map(function ($item){
            return [
                'id'=>$item->id,
                'name'=>$item->name,
                'image' => $item->image,
                'isExtra'=>$item->isExtra,
                'course'=>$item->course,
                'className'=>ClassRoom::find($item->classRoom_id)->name,
            ];
        });

        return response(['children'=>$newChildren]);
    }

    public  function getStatusByChildId(string $id){
        $child=  Child::find($id);
        if(!$child)
        {
            return response(['message'=>'child not found'],404 );

        }

        $classRoom = ClassRoom::find($child->classRoom_id);


        return response([
            'status'=>$classRoom->ageSection->status,
            'course'=>$child->course,
        ]);
    }

    public  function  getExtrasChildren(){
        $response = [
            'children'=>Child::where('isExtra' , 1)->get(),
        ];
        $children = $response['children']->map(function ($item){
            return [
                'id'=> $item->id,
                'name'=>$item->name,
                'isExtra'=>$item->isExtra,
                'image'=>$item->image,
            ];
        });
        return  response(['children'=>$children]);
    }
}
