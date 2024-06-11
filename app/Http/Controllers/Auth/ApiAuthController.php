<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;



class ApiAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $request['password'] = Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $user = User::create($request->toArray());
        try {
            $user->save();
            event(new Registered($user));
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                return response([
                    'token' => $token,
                    'userId' => $user->id,
                    'successMessage' => "User successfully logged in"
                ], 200);
            } else {
                return response(['errors' => "Password mismatch"], 422);
            }
        } else {
            return response(['errors' => "User doesn't exist"], 422);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        return response(['successMessage' => 'You have been successfully logged out!'], 200);
    }
    public function verifyEmail($id, $hash)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Invalid user'], 404);
        }
        if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Invalid verification link'], 401);
        }
        if ($user->hasVerifiedEmail()) {
            return redirect('/')->with('message', 'Email already verified');
        }
        $user->markEmailAsVerified();
        return redirect('/');
    }
}
