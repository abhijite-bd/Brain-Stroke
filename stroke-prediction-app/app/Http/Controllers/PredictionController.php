<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PredictionController
{
    // Show form
    public function index()
    {
        return view('welcome');
    }

    // Handle prediction
    public function predict(Request $request)
    {
        // ✅ Validation
        $validated = $request->validate([
            'age' => 'required|numeric|min:0|max:120',
            'gender' => 'required|in:Male,Female',
            'hypertension' => 'nullable|boolean',
            'heart_disease' => 'nullable|boolean',
            'avg_glucose_level' => 'required|numeric|min:0',
            'bmi' => 'required|numeric|min:0',
            'ever_married' => 'required|in:Yes,No',
            'Residence_type' => 'required|in:Urban,Rural',
            'work_type' => 'required|in:Private,Govt_job,Self-employed,Never_worked,children',
            'smoking_status' => 'required|in:never smoked,formerly smoked,smokes,Unknown',
        ]);

        // ✅ Checkboxes (0 if not checked)
        $validated['hypertension'] = $request->has('hypertension') ? 1 : 0;
        $validated['heart_disease'] = $request->has('heart_disease') ? 1 : 0;

        try {
            // ✅ Send to Flask API
            $response = Http::post('http://127.0.0.1:5000/predict', $validated);

            if ($response->successful()) {
                $prediction = $response->json();
                return back()->with('success', [
                    'risk_level' => $prediction['risk_level'] ?? 'Unknown',
                    'risk_probability' => $prediction['risk_probability'] ?? 0,
                    'result_text' => $prediction['message'] ?? 'Unknown'
                ]);
            } else {
                $error = $response->json();
                return back()->with('error', 'ML API Error: ' . ($error['error'] ?? 'Unknown error') . ' - ' . ($error['details'] ?? 'No details'));
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Server Error: ' . $e->getMessage());
        }
    }
}
