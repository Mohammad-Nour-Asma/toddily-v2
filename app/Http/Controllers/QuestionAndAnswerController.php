<?php

namespace App\Http\Controllers;

use App\Models\QuestionAnswer;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class QuestionAndAnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response(['qa'=> QuestionAnswer::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $fields = $request->validate([
            'question'=> 'required|string',
            'answer' => 'required|string',
        ]);
        $qa = QuestionAnswer::create($fields);

        return response(['qa'=>$qa] , 200 );
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
        $qa = QuestionAnswer::find($id);
        if(!$qa)
        {
            return response(['message'=>'Status not found'],404 );

        }
        $fields =  $request->validate([
            'question' => 'string',
            'answer'=> 'string'
        ]);
        $qa->update($fields);

        return response(['qa'=>$qa,'message'=>'updated successfully' ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $qa = QuestionAnswer::find($id);
        if(!$qa)
        {
            return response(['message'=>'Status not found'],404 );

        }

        $qa->delete();

        return response(['message'=>'deleted successfully' ],200);
    }
}
