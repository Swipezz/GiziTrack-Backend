<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;

class ProfileAPI extends Controller
{
    public function GetProfile(Request $request)
    {
        $user = $request->get('user');

        if ($user) {
            $user->load('employees');
        }

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

}


