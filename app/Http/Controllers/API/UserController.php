<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Resources\ProfileResource;

class UserController extends Controller
{
    public function show(Request $request)
    {   
        return new ProfileResource(
            $request->user()
        );    
    }

    public function update(UserRequest $request)
    {
        $request->user()->update($request->validated());
    }
}
