<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Account;

class AuthAPI extends Controller
{
    public function RequestLogin(Request $request) {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = Account::where('username', $request->username)->first();

        if ($user) {
            $request->session()->put('user_id', $user->id);
            $request->session()->put('username', $user->username);

            return response()->json([
                'status' => 'success',
                'message' => 'Login successfully',
                'data' => [
                    'user_id' => $user->id,
                    'username' => $user->username
                ]
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Wrong username or password'
        ], 401);
    }

    public function RequestRegister(Request $request) {
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

    public function RequestLogout(Request $request) {
        $request->session()->flush();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ]);
    }
}
