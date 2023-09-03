<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventImage;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EventImagesController extends Controller
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
        $request->validate([
            'event_id'=>'numeric|required',
            'images.*' => 'required|image|mimes:jpg,png,jpeg,gif,svg',
        ]);


        $images = $request->file('images');

        foreach ($images as $item) {

            EventImage::create(['event_id'=>$request->event_id , 'src'=>$item]);

        }
        $evenet = Event::with('eventImages')->find($request->event_id);
        return response(['event' => $evenet ],200);
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

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $image = EventImage::find($id);

        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        // Delete the image file from the storage
        $imagePath = public_path($image->src);

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Delete the image record from the database
        $image->delete();

        return response()->json(['message' => 'Image deleted successfully']);

    }
}
