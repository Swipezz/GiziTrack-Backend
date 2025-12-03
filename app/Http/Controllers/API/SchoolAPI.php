<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\School;

class SchoolAPI extends Controller
{
    public function GetListSchool() {
        $schools = School::select('id', 'logo', 'name', 'location', 'total_meal')->get();

        return response()->json([
            'status' => 'success',
            'data' => $schools
        ]);
    }

    public function GetDetailSchool($id) {
        $school = School::select('id', 'logo', 'name', 'location', 'total_student', 'total_meal', 'type_allergy')
                        ->where('id', $id)
                        ->firstOrFail();

        if (!$school) {
            return response()->json([
                'status' => 'error',
                'message' => 'School not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $school
        ]);
    }

    public function UpdateSchool(Request $request, $id) {
        $school = School::find($id);

        if (!$school) {
            return response()->json([
                'status' => 'error',
                'message' => 'School not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'total_student' => 'required|integer',
            'total_meal' => 'required|integer',
            'type_allergy' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/logo'), $filename);
            $validated['logo'] = 'uploads/logo/' . $filename;
        } else {
            $validated['logo'] = $school->logo;
        }

        $validated['type_allergy'] = (string) $request->input('type_allergy');

        $school->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $school
        ], 200);
    }

    public function InsertSchool(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'total_student' => 'required|integer',
            'total_meal' => 'required|integer',
            'type_allergy' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/logo'), $filename);

            $validated['logo'] = 'uploads/logo/' . $filename;
        } else {
            $validated['logo'] = 'uploads/logo/default.jpg';
        }

        $validated['type_allergy'] = (string) $request->input('type_allergy');

        $school = School::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => $school
        ], 200);
    }

    public function DeleteSchool($id) {
        $school = School::find($id);

        if (!$school) {
            return response()->json([
                'status' => 'error',
                'message' => 'School not found'
            ], 404);
        }

        if ($school->logo && $school->logo !== 'uploads/logo/default.jpg' && file_exists(public_path($school->logo))) {
            unlink(public_path($school->logo));
        }

        $school->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Delete school successfully'
        ], 200);
    }
}
