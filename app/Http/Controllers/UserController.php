<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class UserController extends Controller
{
    public function login(Request $request)
    {
        /** @var User|null */
        $user = User::where(['email' => $request->email])->first();

        if(is_null($user) || !Hash::check($request->password, $user->password)) {
            throw new AuthenticationException();
        }
        $user->tokens()->delete();

        $token = $user->createToken($user->id)->plainTextToken;

        return response()->json(['token' => $token]);
    }

    public function create(Request $request)
    {
        $user = new User();

        if($user->existsEmail($request->email))
            throw new ConflictHttpException();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->isAdmin = $request->isAdmin;
        $user->saveOrFail();

        return response()->json(new UserResource($user));
    }

    public function update(Request $request)
    {
        /** @var User */
        $user = User::where(['id' => $request->id])->first();

        if($user->existsEmail($request->email))
            throw new ConflictHttpException();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->isAdmin = $request->isAdmin;
        $user->saveOrFail();

        return response()->json(new UserResource($user));
    }
}
