<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $reports = Report::all();
        return response(['reports' => $reports],200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $fields = $request->validate([
            'description' => 'string|required',
            'child_id'=>'numeric|required',
        ]);

        $report = Report::create($fields);
        return response(['report'=>$report] , 200);
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
        $report = Report::find($id);
        if(!$report)
        {
            return response(['message'=>'report not found'],404 );
        }

        $fields =  $request->validate([
            'description' => 'string',
            'child_id'=> 'numeric'
        ]);
        $report->update($fields);

        return response(['report'=> $report ,'message'=>'updated successfully' ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $report = Report::find($id);
        if(!$report)
        {
            return response(['message'=>'report not found'],404 );

        }
        $report->delete();

        return response(['message'=>'deleted Successfully'],200 );

    }

    public  function  childReports(string $id){

        $child = Child::find($id);
        if(!$child)
        {
            return response(['message'=>'child not found'],404 );

        }
        return  response(['reports' => $child->report],200);
    }
}
