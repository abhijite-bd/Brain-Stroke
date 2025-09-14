<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stroke Risk Predictor</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }

        .card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .form-control {
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn-primary {
            background: linear-gradient(to right, #3b82f6, #2563eb);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-download {
            background: linear-gradient(to right, #10b981, #059669);
        }

        .btn-download:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="container mx-auto px-4 py-12 max-w-3xl">
        <div class="card p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Stroke Risk Assessment</h1>

            <!-- Validation Errors -->
            @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Prediction Result -->
            @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                <p><strong>Risk Level:</strong> {{ session('success')['risk_level'] }}</p>
                <p><strong>Probability:</strong> {{ session('success')['risk_probability'] }}</p>
                <p><strong>Result:</strong> {{ session('success')['result_text'] }}</p>
                <button id="download-report" class="mt-4 btn-download text-white font-medium py-2 px-4 rounded-md">Download Report</button>
            </div>
            @endif

            @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                {{ session('error') }}
            </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('predict') }}" id="stroke-form" class="space-y-6">
                @csrf
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" name="name" id="name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm form-control @error('name') border-red-500 @enderror"
                        value="{{ old('name') }}" required>
                    @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Age -->
                <div>
                    <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
                    <input type="number" name="age" id="age"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm form-control @error('age') border-red-500 @enderror"
                        value="{{ old('age') }}" required min="0" max="120" step="0.1">
                    @error('age')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gender -->
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                    <select name="gender" id="gender"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm form-control @error('gender') border-red-500 @enderror" required>
                        <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select Gender</option>
                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                    @error('gender')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hypertension -->
                <div class="flex items-center">
                    <input type="checkbox" name="hypertension" id="hypertension"
                        class="h-4 w-4 text-blue-600 border-gray-300 rounded" value="1" {{ old('hypertension') ? 'checked' : '' }}>
                    <label for="hypertension" class="ml-2 text-sm text-gray-700">Hypertension</label>
                </div>

                <!-- Heart Disease -->
                <div class="flex items-center">
                    <input type="checkbox" name="heart_disease" id="heart_disease"
                        class="h-4 w-4 text-blue-600 border-gray-300 rounded" value="1" {{ old('heart_disease') ? 'checked' : '' }}>
                    <label for="heart_disease" class="ml-2 text-sm text-gray-700">Heart Disease</label>
                </div>

                <!-- Average Glucose Level -->
                <div>
                    <label for="avg_glucose_level" class="block text-sm font-medium text-gray-700">Average Glucose Level (mg/dL)</label>
                    <input type="number" name="avg_glucose_level" id="avg_glucose_level"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm form-control @error('avg_glucose_level') border-red-500 @enderror"
                        value="{{ old('avg_glucose_level') }}" required min="0" step="0.1">
                    @error('avg_glucose_level')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- BMI -->
                <div>
                    <label for="bmi" class="block text-sm font-medium text-gray-700">BMI</label>
                    <input type="number" name="bmi" id="bmi"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm form-control @error('bmi') border-red-500 @enderror"
                        value="{{ old('bmi') }}" required min="0" step="0.1">
                    @error('bmi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ever Married -->
                <div>
                    <label for="ever_married" class="block text-sm font-medium text-gray-700">Ever Married</label>
                    <select name="ever_married" id="ever_married"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm form-control @error('ever_married') border-red-500 @enderror" required>
                        <option value="" disabled {{ old('ever_married') ? '' : 'selected' }}>Select</option>
                        <option value="Yes" {{ old('ever_married') == 'Yes' ? 'selected' : '' }}>Yes</option>
                        <option value="No" {{ old('ever_married') == 'No' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <!-- Residence Type -->
                <div>
                    <label for="Residence_type" class="block text-sm font-medium text-gray-700">Residence Type</label>
                    <select name="Residence_type" id="Residence_type"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm form-control @error('Residence_type') border-red-500 @enderror" required>
                        <option value="" disabled {{ old('Residence_type') ? '' : 'selected' }}>Select</option>
                        <option value="Urban" {{ old('Residence_type') == 'Urban' ? 'selected' : '' }}>Urban</option>
                        <option value="Rural" {{ old('Residence_type') == 'Rural' ? 'selected' : '' }}>Rural</option>
                    </select>
                </div>

                <!-- Work Type -->
                <div>
                    <label for="work_type" class="block text-sm font-medium text-gray-700">Work Type</label>
                    <select name="work_type" id="work_type"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm form-control @error('work_type') border-red-500 @enderror" required>
                        <option value="" disabled {{ old('work_type') ? '' : 'selected' }}>Select</option>
                        <option value="Private" {{ old('work_type') == 'Private' ? 'selected' : '' }}>Private</option>
                        <option value="Govt_job" {{ old('work_type') == 'Govt_job' ? 'selected' : '' }}>Government Job</option>
                        <option value="Self-employed" {{ old('work_type') == 'Self-employed' ? 'selected' : '' }}>Self-employed</option>
                        <option value="Never_worked" {{ old('work_type') == 'Never_worked' ? 'selected' : '' }}>Never Worked</option>
                        <option value="children" {{ old('work_type') == 'children' ? 'selected' : '' }}>Children</option>
                    </select>
                </div>

                <!-- Smoking Status -->
                <div>
                    <label for="smoking_status" class="block text-sm font-medium text-gray-700">Smoking Status</label>
                    <select name="smoking_status" id="smoking_status"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm form-control @error('smoking_status') border-red-500 @enderror" required>
                        <option value="" disabled {{ old('smoking_status') ? '' : 'selected' }}>Select</option>
                        <option value="never smoked" {{ old('smoking_status') == 'never smoked' ? 'selected' : '' }}>Never Smoked</option>
                        <option value="formerly smoked" {{ old('smoking_status') == 'formerly smoked' ? 'selected' : '' }}>Formerly Smoked</option>
                        <option value="smokes" {{ old('smoking_status') == 'smokes' ? 'selected' : '' }}>Smokes</option>
                        <option value="Unknown" {{ old('smoking_status') == 'Unknown' ? 'selected' : '' }}>Unknown</option>
                    </select>
                </div>

                <button type="submit" class="w-full btn-primary text-white font-medium py-3 rounded-md">Predict Risk</button>
            </form>
        </div>
        <p class="mt-6 text-center text-sm text-gray-500">Developed for health awareness. Consult a medical professional for accurate diagnosis.</p>
    </div>

    <script>
        document.getElementById('download-report')?.addEventListener('click', () => {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();
            const name = document.getElementById('name').value || 'Patient';
            const riskLevel = "{{ session('success')['risk_level'] ?? 'N/A' }}";
            const probability = "{{ session('success')['risk_probability'] ?? 'N/A' }}";
            const resultText = "{{ session('success')['result_text'] ?? 'N/A' }}";
            const age = document.getElementById('age').value || 'N/A';
            const gender = document.getElementById('gender').value || 'N/A';
            const hypertension = document.getElementById('hypertension').checked ? 'Yes' : 'No';
            const heartDisease = document.getElementById('heart_disease').checked ? 'Yes' : 'No';
            const glucoseLevel = document.getElementById('avg_glucose_level').value || 'N/A';
            const bmi = document.getElementById('bmi').value || 'N/A';
            const everMarried = document.getElementById('ever_married').value || 'N/A';
            const residenceType = document.getElementById('Residence_type').value || 'N/A';
            const workType = document.getElementById('work_type').value || 'N/A';
            const smokingStatus = document.getElementById('smoking_status').value || 'N/A';

            doc.setFontSize(18);
            doc.text('Stroke Risk Assessment Report', 20, 20);
            doc.setFontSize(12);
            doc.text(`Name: ${name}`, 20, 30);
            doc.text(`Date: ${new Date().toLocaleDateString()}`, 20, 40);
            doc.setLineWidth(0.5);
            doc.line(20, 45, 190, 45);

            doc.text('Assessment Results', 20, 55);
            doc.text(`Risk Level: ${riskLevel}`, 20, 65);
            doc.text(`Probability: ${probability}`, 20, 75);
            doc.text(`Result: ${resultText}`, 20, 85);

            doc.text('Input Parameters', 20, 95);
            doc.text(`Age: ${age}`, 20, 105);
            doc.text(`Gender: ${gender}`, 20, 115);
            doc.text(`Hypertension: ${hypertension}`, 20, 125);
            doc.text(`Heart Disease: ${heartDisease}`, 20, 135);
            doc.text(`Average Glucose Level: ${glucoseLevel} mg/dL`, 20, 145);
            doc.text(`BMI: ${bmi}`, 20, 155);
            doc.text(`Ever Married: ${everMarried}`, 20, 165);
            doc.text(`Residence Type: ${residenceType}`, 20, 175);
            doc.text(`Work Type: ${workType}`, 20, 185);
            doc.text(`Smoking Status: ${smokingStatus}`, 20, 195);

            doc.text('Note: This is an automated assessment. Consult a medical professional for accurate diagnosis.', 20, 205);
            doc.save(`Stroke_Risk_Report_${name.replace(/\s+/g, '_')}.pdf`);
        });
    </script>
</body>

</html>