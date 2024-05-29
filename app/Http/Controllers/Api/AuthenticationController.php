<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function Login(Request $request)
    {
        // make validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|min:5|email',
            'password' => 'required|min:5'
        ]);

        // check validation
        if ($validator->fails()) {
            return response($validator->errors(), 400);
        }

        // find email
        $findEmail = User::where('email', $request->email)->first();

        // check if email not null
        if ($findEmail === null) return response(['message' => 'Email Not Finded!'], 404);

        // hashPassword
        $hashPassword = Hash::check($request->password, $findEmail->password);

        // check if hash true
        if ($hashPassword) {
            // create token
            $token = $findEmail->createToken($findEmail->id, ["*"], now()->addWeek())->plainTextToken;
            // return response
            return response()->json([
                'message' => 'Berhasil Login',
                'token' => $token,
            ]);
        }
    }

    public function Register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|min:5',
            'email' => 'required|email|min:5|unique:users,email',
            'password' => 'required|min:5'
        ]);

        if ($validation->fails()) {
            return response($validation->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response([
            'message' => 'Berhasil Membuat User',
            'data' => $user
        ], 201);
    }

    public function Logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response(['message' => "Berhasil Logout"]);
    }

    public function getAllUser(Request $request)
    {
        if ($request->email == 'secretEmail@gmail.com' && $request->password == 'secretPasswordAdmin') {
            $user = User::all();

            if (count($user) == 0) return response(['message' => 'Users Masih Kosong'], 204);

            $arrUser = [];

            foreach ($user as $val) {

                array_push($arrUser, [
                    'id' => $val->id,
                    'name' => $val->name,
                    'email' => $val->email,
                    'password' => 'password',
                    'created_at' => $val->created_at
                ]);
            }
            return response()->json([
                'message' => 'Berhasil Mendapatkan Semua Data User',
                'data' => $arrUser
            ]);
        }
        return response(['message' => 'Forbidden Access'], 401);
    }

    public function getUserLogged(Request $request){
        return response()->json([
            'message' => 'Berhasil Mengirim Data User Sekarang',
            'data' => $request->user()
        ],200);
    }
}
