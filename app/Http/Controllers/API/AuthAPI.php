<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Account;

class AuthAPI extends Controller
{
    public function RequestLogin(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required',
                'password' => 'required',
            ]);

            $user = Account::where('username', $request->username)->first();

            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'Username not found'], 401);
            }

            if (!password_verify($request->password, $user->password)) {
                return response()->json(['status' => 'error', 'message' => 'Wrong password'], 401);
            }

            $token = bin2hex(random_bytes(32));
            $user->api_token = $token;
            $user->save();

            $cookie = cookie(
                'api_token',
                $token,
                60 * 24,
                '/',
                null,
                true,
                true,
                false,
                'None'
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Login successfully',
                'data' => [
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'api_token' => $token // tambahkan ini sementara untuk testing
                ]
            ])->cookie($cookie);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Exception: ' . $e->getMessage()
            ], 500);
        }
    }

    public function RequestRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:account,username|max:255',
            'password' => 'required|min:4',
            'office' => 'required|string',
            'employee' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $account = new Account();
        $account->username = $request->username;
        $account->password = bcrypt($request->password);
        $account->office = $request->office;
        $account->employee = $request->employee;
        $account->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Registration successfully',
            'data' => [
                'id' => $account->id,
                'username' => $account->username,
                'office' => $account->office,
                'employee' => $account->employee,
            ]
        ], 200);
    }

    public function RequestLogout(Request $request)
    {
        try {
            $token = $request->cookie('api_token');

            if ($token) {
                $user = Account::where('api_token', $token)->first();
                if ($user) {
                    $user->api_token = null;
                    $user->save();
                }
            }

            $cookie = cookie(
                'api_token',
                null,
                -1,
                '/',
                null,      // domain current host
                true,      // secure (ubah true jika HTTPS di production)
                true,      // httpOnly
                false,
                'None'
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Logged out successfully'
            ])->cookie($cookie);

        } catch (\Throwable $e) {
            $cookie = cookie(
                'api_token',
                null,
                -1,
                '/',
                null,
                true,
                true,
                false,
                'None'
            );

            return response()->json([
                'status' => 'error',
                'message' => 'Logout failed: ' . $e->getMessage()
            ], 500)->cookie($cookie);
        }
    }
}
