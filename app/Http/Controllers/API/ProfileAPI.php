<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

use App\Models\Account;

class ProfileAPI extends Controller
{
    public function GetProfile() {
        $userId = Session::get('user_id');

        if (!$userId) {
            return response()->json([
                'status' => 'error',
                'message' => 'User is not logged in or session not found'
            ], 401);
        }

        $account = Account::with('employees')->find($userId);

        if (!$account) {
            return response()->json([
                'status' => 'error',
                'message' => 'Account not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $account
        ], 200);
    }
}

