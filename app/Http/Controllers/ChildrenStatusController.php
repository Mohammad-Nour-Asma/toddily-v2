<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\ChildCourseStatus;
use App\Models\ChildStatus;
use App\Models\ChildSubstatus;
use App\Models\Status;
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
            'status.*.status_id' => 'required|numeric',
            'status.*.substatus.*.substatus_id' => 'required|numeric',
            'status.*.substatus.*.description' => 'required|string',

        ];
        $request->validate($rules);

        foreach ($request->get('status') as $status){


            $childStatus =ChildStatus::create([
                'child_id'=>$request->get('child_id'),
                'status_id'=>$status['status_id'],
            ]);
            foreach ($status['substatus'] as $subStatus){
                ChildSubstatus::create([
                    'childStatus_id'=>$childStatus->id,
                    'subStatus_id' => $subStatus['substatus_id'],
                    'description' => $subStatus['description']
                ]);
            }
        }

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






         $courses = $child->course;
        $newrep = $courses->map(function ($item)use ($request){
          $status =   $item->status;
            $filtered = $status->map(function ($item)use ($request) {
                $date1 = Carbon::parse($item->created_at);
                $formattedDate = $date1->format('Y-n-j');
                if($formattedDate == $request->get('date')) {
                    return $item;
                }
            });

            return ['course'=>$item,'status'=>$filtered];
        });

        return response([
            'status' => $records,
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

        $uniqueDates = collect($data)->unique('date');
        return response(['data'=>$uniqueDates]);
    }
}
