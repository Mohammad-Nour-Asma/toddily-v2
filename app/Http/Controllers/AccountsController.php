<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Child;
use App\Models\ChildParent;
use App\Models\ClassRoom;
use App\Models\Role;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        if (request()->get('type') == 'parent'){
            return response([
                'data' => UserResource::collection(User::whereHas('role' , fn($query) =>
                $query->where('role_name' , 'parent')
                )->get())
            ], 200);
        }else if (request()->get('type') == 'stuff'){
            return response([
                'data' => UserResource::collection(User::whereHas('role' , fn($query) =>
                $query->where('role_name' , '!=' , 'parent')
                )->get())
            ], 200);
        }
        return response([
            'data' => UserResource::collection(User::all())
        ], 200);
    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request -> validate([
            'first_name'=> 'required|string ',
            'last_name'=> 'required|string ',
            'phone' => 'required|string',
            'role_name'=> ['required','string' , Rule::exists('roles' , 'role_name')]
            ]
        );

        // Generate a unique username based on the name
        $username = strtolower(str_replace(' ', '', $request->first_name)); // Convert name to lowercase and remove spaces
        $username .= '@'.'toddily'.DB::table('Users')->latest()->first()->id + 1; // Append a unique identifier


        $password = Str::random(8);
        $password = Str::lower($password);

        $role_id = Role::where('role_name' ,$request->get('role_name'))->first()['id'];


        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
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
            "user" => UserResource::make($user),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $fields = $request->validate([
            'first_name'=>'string',
            'last_name'=>'string',
            'phone'=>'string',
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
    public function stats(){
        try {
            $parentNumber = User::where('role_id' , 5)->count();
            $teacherNumber = User::where('role_id' , 2)->count();
            $classNumber = ClassRoom::count();
            $childrenNumber = Child::count();

            return response([
                'parents_number' => $parentNumber,
                'teachers_number' => $teacherNumber,
                'classes_number' => $classNumber,
                'kids_number' => $childrenNumber
            ] , 200);
        }catch(\Throwable $th){
            return response([
                'message' => $th->getMessage()
            ] , 500);
        }
    }
}
