<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\ChildImage;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ChildImagesController extends Controller
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

        $fields = $request->validate([
            'child_id'=>'numeric|required',
            'image' => 'image',
        ]);

        if($request->hasFile('image')){
            $image = $request->file('image');
            $filename = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $filename);
            $fields['image'] = '/images/'.$filename;
        }

        $childImage = ChildImage::create(
           array_merge($fields ,['teacher_checked'=>false])
        );

        return response(['childImage' => $childImage],200);
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $childImage = ChildImage::find($id);

        if (!$childImage) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        // Delete the image file from the storage
        $imagePath = public_path($childImage->image);

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Delete the image record from the database
        $childImage->delete();

        return response()->json(['message' => 'Image deleted successfully']);

    }

    public function getChildImages(string $id){
        $child = Child::find($id);
        if(!$child){
            return response(['message'=>'not Found'],400);
        }


        return response (['images'=>$child->images],200);
    }

    public function makeImageChecked(string $id){
        $image = ChildImage::find($id);
        if(!$image){
            return response(['message'=>'not Found'],400);
        }

        $image->update([
            'teacher_checked'=>true
        ]);

        return response (['imagee'=>$image],200);
    }

    public function getChildImagesForParents(string $id){
        $child = Child::find($id);

        if(!$child){
            return response(['message'=>'not Found'],400);
        }


        return response (['images'=>$child->images->where('teacher_checked' , '1')],200);
    }
}
