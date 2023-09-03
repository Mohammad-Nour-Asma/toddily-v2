<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $posts = Post::all();
        return response(['posts' =>$posts] , 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $fields = $request->validate([
            'image'=>'image|required',
        ]);

        if($request->hasFile('image')){
            $image = $request->file('image');
            $filename = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $filename);
            $fields['image'] = '/images/'.$filename;
        }

        $post = Post::create(
         [  'image_url'=> $fields['image'],]
        );


        return response(['post' =>$post ,'message' => 'created successfully'], 200);
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
            'image'=>'image|required',
        ]);


        $post = Post::find($id);


        if(!$post){
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
        $imagePath = public_path($post->image_url);

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $post->update(['image_url'=>$fields['image']]);
        return response([
            'post' => Post::find($id),
            'message'=> 'Updated Successfully',

        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        // Delete the image file from the storage
        $imagePath = public_path($post->image_url);

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Delete the image record from the database
        $post->delete();

        return response()->json(['message' => 'post deleted successfully']);

    }
}
