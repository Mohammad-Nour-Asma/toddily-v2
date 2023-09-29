<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\ChildCourseStatus;
use App\Models\ChildStatus;
use App\Models\ChildSubstatus;
use App\Models\Status;
use App\Models\Substatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ChildrenStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $childStatus = ChildStatus::with(['childSubstatus' , 'status'])->get();
        return response([$childStatus]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $rules = [
            'child_id' => 'required|numeric',
            'substatus.*.substatus_id' => 'required|numeric',
            'substatus.*.description' => 'string|nullable',
        ];
        $request->validate($rules);

        foreach ($request->get('substatus') as $substatus){

            $status_id= Substatus::find($substatus['substatus_id'])->status->id;

            $childStatus =ChildStatus::create([
                'child_id'=>$request->get('child_id'),
                'status_id'=>$status_id,
            ]);



                ChildSubstatus::create([
                    'childStatus_id'=>$childStatus->id,
                    'subStatus_id' => $substatus['substatus_id'],
                    'description' => $substatus['description']
                ]);

        }

        NotificationController::sendStatusNotification($request->child_id);

        return response(['message'=>'created successfully'],200);
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
        $childStatus = ChildStatus::find($id);
        if(!$childStatus){
            return response(['message'=>'not Found'],400);
        }


        $childStatus->delete();

        return response(['message'=>'deleted Successfully'],200);
    }

    public function getStatusByDate( Request $request ,string $id){
        if(!($child = Child::find($id))){
            return response(['message'=>'not Found'],400);
        }
        $request ->validate([
            'date' => 'required|date'
        ]);

        $records = ChildStatus::whereDate('created_at', '=', $request->get('date'))
            ->where('child_id' , $id)->get();


         $courses = $child->course->map(function ($item){
             return
                 [
                 'child_course_id'=>$item['course_id'],
                 'course_name'=>$item->course->name,
                 'status'=>$item->status->map(function ($status){
                     return ['id'=>$status['id'],"description" => $status['description']];
                 }),
                 ];
         });

        $newrep = $courses->map(function ($item)use ($request){
          $status =   $item->status;
            $filtered = $status->map(function ($item)use ($request) {
                $date1 = Carbon::parse($item->created_at);
                $formattedDate = $date1->format('Y-n-j');
                if($formattedDate == $request->get('date')) {
                    return  [
                        'child_course_id'=>$item['course_id'],
                        'course_name'=>$item->course->name,
                        'status'=>$item->status->map(function ($status){
                            return ['id'=>$status['id'],"description" => $status['description']];
                        }),
                    ];
                }
            });

            return ['course'=>$item,'status'=>$filtered];
        });

        $resource = $records->map(function ($item){
            return [
                'name'=>$item->status->name,
                'status_id'=>$item->status->id,
                'substatus'=> $item->childSubstatus->map(function ($item){
                    return[
                        'name'=> $item->substatus->name,
                        'image'=>$item->substatus->image,
                        'description'=>$item->description,
                    ];
                })
                ];
        });

        $grouped=  $resource->groupBy('status_id');

        $last = $grouped->map(function ($item){
           $subStatus = [];
           foreach ($item as $status){
               array_push($subStatus , $status['substatus'][0]);
           }
           return ['name' => Status::find($item)->first()->name ,'substatus'=>$subStatus];
        });

        $lastLast=  $last->values();



        return response([
            'status' => $lastLast,
            'courses'=> $newrep
        ]);
    }

    public  function  getStatusDates(string $id){
        $child = Child::find($id);
        if(!$child){
            return response(['message'=>'not Found'],400);
        }

        $data = $child->status->map(function ($item){
            $date = Carbon::parse($item->created_at);
            $year = $date->format('Y');
            $month = $date->format('m');
            $day = $date->format('d');
            $formattedDate = $year . '-' . $month . '-' . $day;
           return ['date'=> $formattedDate];
        });

        $uniqueDates = $data->unique('date');

        return response(['data'=>$uniqueDates->values()]);
    }
}
