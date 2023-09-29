<?php

namespace App\Http\Controllers;

use App\Models\AboutImages;
use Illuminate\Http\Request;

class AboutImagesController extends Controller
{
    //
    public function index()
    {
        //
        $images = AboutImages::all();
        return response(['aboutImages' =>$images] , 200);
    }

    public function store(Request $request)
    {
        //
        $request->validate([
            'images.*' => 'required|image|mimes:jpg,png,jpeg,gif,svg',
            'images' => 'required|array',
        ]);

        $images =($request->file('images'));

        foreach ($images as $item) {
            $image = $item;
            $filename = uniqid().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $filename);
            $src = '/images/'.$filename;
            $AboutImages = AboutImages::create(
                ['image_url'=> $src]
            );
        }
        return response(['message' => 'created successfully'], 200);
    }

    public function destroy(string $id)
    {
        //
        $image = AboutImages::find($id);

        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        // Delete the image file from the storage
        $imagePath = public_path($image->image_url);

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Delete the image record from the database
        $image->delete();

        return response()->json(['message' => 'Image deleted successfully']);

    }



}
