<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Repositories\User;

class UserController extends BaseController
{
    public function store(Request $request, User $user)
    { 
        $user->storeUser($request);
    }
}
