<?php

namespace App\Http\Controllers;

use App\Models\ChildParent;
use App\Models\Role;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $data =  User::with('role')->get();


        if($request->get('search') == 'staff'){
          $staff = $data->filter(function ($item){return $item->role->role_name != 'parent';});

            return response([
                'data' => $staff
            ], 200);

        }else if ($request->get('search') == 'parent'){
            $parent = $data->filter(function ($item){return $item->role->role_name == 'parent';});
            return response([
                'data' => $parent
            ], 200);
        }
        return response([
            'data' => User::with('role')->get()
        ], 200);
    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request -> validate([
            'name'=> 'required|string ',
            'role_name'=> 'required|string',
            'phone'=> 'required|string'
        ]);

        // Generate a unique username based on the name
        $username = strtolower(str_replace(' ', '', $request->name)); // Convert name to lowercase and remove spaces
        $username .= '@'.'toddily'.DB::table('Users')->latest()->first()->id + 1; // Append a unique identifier


        $password = Str::random(8);
        $password = Str::lower($password);

        $role_id = Role::where('role_name' ,$request->get('role_name'))->first()['id'];


        if(!$role_id){
            return response(['message'=> 'not found' ], 404);
        }


        $user = User::create([
            'name' => $request->get('name'),
            'password' => $password,
            'username' => $username,
            'role_id' => $role_id,
            'phone' => $request->get('phone')
        ]);

        return response([
            'user' => $user,
            'password' => $password,
            'message' => 'account create successfully'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $user = User::with(['role' , 'parent'])->find($id);
        return response([
            "user" => $user ,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $fields = $request->validate([
            'name'=>'string',
            'role_id'=>'numeric',
            'phone' => 'string',
        ]);
        $user = User::find($id);
        $user->update(
            $fields
        );
        return response(['user' => $user , 'message' => 'updated successfully'] , 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $user = User::find($id);
        if(!$user)
        {
            return response(['message'=>'age section not found'],404 );

        }

        $user->delete();
        return response(['message'=>'Account Deleted Successfully'] , 200);
    }

    public function resetPassword(Request $request , string $id){
        $password = Str::random(8);
        $user = User::find($id);
        if(!$user)
        {
            return response(['message'=>'age section not found'],404 );

        }

        $user->update([
            'password'=>$password,
        ]);
        return response(['newPassword' => $password ,'message'=>'Updated Successfully'] , 200);

    }
}
