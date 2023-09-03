<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RoleController extends Controller
{
    //
    public function index(){
        $response  = Role::all();
        return response(['data' => $response]);
    }
}
