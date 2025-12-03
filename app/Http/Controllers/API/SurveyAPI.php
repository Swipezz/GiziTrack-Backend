<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\SurveyFood;
use App\Models\SurveyAllergy;

class SurveyAPI extends Controller
{
    public function SurveyFood(Request $request) {
        $validator = Validator::make($request->all(), [
            'school' => 'required|string|exists:schools,name',
            'food' => 'required|array',
            'food.*' => 'required|string|max:255',
            'total' => 'required|array',
            'total.*' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validator error',
                'errors' => $validator->errors()
            ], 422);
        }

        $school = $request->input('school');
        $foods = $request->input('food');
        $totals = $request->input('total');

        $recordsCreated = 0;

        for ($i = 0; $i < count($foods); $i++) {
            $food = trim($foods[$i]);
            $total = (int) trim($totals[$i]);

            if ($food === '' || $total < 0) {
                continue; 
            }

            SurveyFood::create([
                'school' => $school,
                'food'   => $food,
                'total'  => $total,
            ]);

            $recordsCreated++;
        }

        return response()->json([
            'status' => 'success',
        ], 200);
    }

    public function surveyAllergy(Request $request) {
        $validator = Validator::make($request->all(), [
            'school' => 'required|string|exists:schools,name',
            'allergy' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validator error',
                'errors' => $validator->errors()
            ], 422);
        }

        $school = $request->input('school');
        $allergy = $request->input('allergy');

        SurveyAllergy::create([
            'school' => $school,
            'allergy' => $allergy,
        ]);

        return response()->json([
            'status' => 'success',
        ], 200);
    }
}