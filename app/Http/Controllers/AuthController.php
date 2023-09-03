<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\AgeSection;
use App\Models\Child;
use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $fields = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $fields['username'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'The provided credentials are incorrect',
            ], 422);
        }
        $token = $user->createToken('token')->plainTextToken;

        if($user->role->role_name == 'extra' ) {
            $response = [
                'user' => new UserResource($user),
                'children'=>Child::where('isExtra' , 1)->get(),
                'token'=>$token,
            ];
            return response($response , 201);

        }

  if($user->role->role_name == 'doctor' || $user->role->role_name == 'social' ) {
            $response = [
                'user' => new UserResource($user),
                'classes'=>ClassRoom::all(),
                'token'=>$token,
            ];
            return response($response , 201);

        }

         if($user->role->role_name == 'parent') {
            $response = [
                'user' => new UserResource($user),
                'children'=> $user->children,
                'token'=>$token,
            ];
            return response($response , 201);

        }

            if($user->role->role_name == 'teacher' && $user->classRoom){
           $cheldren = $user->classRoom->children;
           $ageSection = AgeSection::find($user->classRoom->age_section_id);
           $status = $ageSection->status;


            foreach ($status as $s) {
              $substatus =   $s->substatus;
            }

            $response = [
                'user' => new UserResource($user),
                'class'=>$user->classRoom,
                'status'=>$status,
                'token'=>$token,
            ];
            return response($response , 201);

        }

        $response = [
           'user' => new UserResource($user),
            'token'=>$token,
        ];

        return response($response , 201);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return response(['message'=>'Logged out'], 200);
    }

    public function getCurrentUser(Request $request)
    {
        $request->user()->role->role_name;
        return response(['user'=>$request->user()]);
    }

}
