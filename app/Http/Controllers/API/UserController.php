<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request)
    {

        try {
            // Validasi input
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            // Mengecek credentials (login)
            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error([
                    'message' => 'Unauthorized'
                ], 'Autentication Failed', 500);
            }

            //Jika hash tidak sesuai maka beri error
            $user = User::where('email', $request->email)->first();
            if (!Hash::check($request->getPassword, $user->password, [])) {
                throw new \Exception('Invalid Credentials');
            }

            //Jika berhasil maka boleh silahkan login
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 'Autenticated');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Autentication Filed', 500);
        }
    }
}
