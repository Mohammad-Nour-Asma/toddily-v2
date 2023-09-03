<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $events = Event::with('eventImages')->get();
        return response(['events' =>$events] , 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
          $fields = $request->validate([
              'name' => 'string|required',
              'image_cover'=>'image|required',
        ]);

        if($request->hasFile('image_cover')){
            $image = $request->file('image_cover');
            $filename = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $filename);
            $fields['image_cover'] = '/images/'.$filename;
        }
          $event = Event::create(
              $fields
          );


        return response(['event' =>$event ,'message' => 'created successfully'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $event = Event::with('eventImages')->find($id);
        if(!$event){
            return response(['message'=>'not Found'],400);
        }
        return response(['event'=>$event],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //


        $fields = $request->validate([
            'name' => 'string',
            'image_cover'=>'image',
        ]);


        $event = Event::find($id);


        if(!$event){
            return response(['message'=>'not Found'],400);
        }



        if($request->hasFile('image_cover')){
            $image = $request->file('image_cover');
            $filename = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $filename);
            $fields['image_cover'] = '/images/'.$filename;
        }

        // Delete The old Image
        $imagePath = public_path($event->image_cover);

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $event->update($fields);
        return response([
            'event' => Event::find($id),
            'message'=> 'Updated Successfully',

        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $event = Event::with('eventImages')->find($id);
        if(!$event){
            return response(['message'=>'not Found'],400);
        }

        $imagePath = public_path($event->image_cover);

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $event->delete();

        return response(['message'=>'deleted Successfully'],200);

    }
}
