<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data['password'] = bcrypt($request->password);
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $user = User::create($data);
        $success['token'] =  $user->createToken('SanctumAPI')->plainTextToken;
        $success['name'] =  $user->name;
        return sendSuccessResponse($success, 'User register successfully.', 201);
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('SanctumAPI')->plainTextToken;
            $success['name'] =  $user->name;

            return sendSuccessResponse($success, 'User login successfully.');
        } else {
            return sendErrorResponse('Unauthorized.', ['error' => 'Unauthorized'], 401);
        }
    }
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
}
