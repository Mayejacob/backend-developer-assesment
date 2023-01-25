<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResources;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    public function index()
    {
        $users = User::all();
        return $this->sendResponse(UserResources::collection($users), 'Users fetched successfully.');
    }
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }
        $user = User::create($input);
        return $this->sendResponse(new UserResources($user), 'Registration successful.');
    }
    public function show($id)
    {
        $User = User::find($id);
        if (is_null($User)) {
            return $this->sendError('User Post does not exist.');
        }
        return $this->sendResponse(new UserResources($User), 'User fetched successfully.');
    }
    public function update(Request $request, User $user)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|email',
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }
        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->save();
        
        return $this->sendResponse(new UserResources($user), 'User updated successfully.');
    }
    public function destroy(User $user)
    {
        $user->delete();
        return $this->sendResponse([], 'User deleted successfully.');
    }
}
