<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use  App\User;
use DB;

class AuthController extends Controller
{
    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
     public function login(Request $request)
      {
            //validate incoming request
          $this->validate($request, [
              'member_id' => 'required|string',
              'latitude' => 'required|string',
              'longitude' => 'required|string',
              'password' => 'required|string',
          ]);

          $credentials = $request->only(['member_id', 'password']);

          if (! $token = Auth::attempt($credentials)) {
              return response()->json(['message' => 'Unauthorized'], 401);
          }else{

            $result = $this->respondWithToken($token);
            $users = User::where(['member_id'=>$request->member_id,'delete_user'=>0]) -> first();
            $response = [
              'user_id' => $users->user_id,
              'member_id' => $users->member_id,
              'phone' => $users->phone,
              'role_id' => $users->role_id,
              'kyc_status' => $users->kyc_status,
              'user_status' => $users->user_status,
              'user_type' => $users->user_type,
              'latitude' => $request->latitude,
              'longitude' => $request->longitude,
              'token' => $result->original['token'],
              'token_type' => $result->original['token_type'],
              'expires_in' => $result->original['expires_in'],
            ];
            return response( $response );
          }

      }


}
