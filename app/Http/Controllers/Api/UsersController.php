<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UpdateUserRequest;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'desc')->get();
        return response()->json(compact('users'));
    }

    public function show($id)
    {
        $user = User::find($id);
        return response()->json(compact('user'));
    }

    public function update(UpdateUserRequest $request, $id)
    {
        User::find($id)->update($request->validated());
        return response('OK', 200);
    }
}
