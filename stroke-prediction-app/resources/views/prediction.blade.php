<!DOCTYPE html>
<html>

<head>
    <title>Stroke Risk Predictor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Stroke Risk Assessment</h2>
        @if ($errors->any())
        <div class="alert alert-danger">{{ implode('', $errors->all()) }}</div>
        @endif
        <form method="POST" action="{{ route('predict') }}">
            @csrf
            <div class="mb-3">
                <label>Age: <input type="number" name="age" class="form-control" required></label>
            </div>
            <div class="mb-3">
                <label>Gender:
                    <select name="gender" class="form-control" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </label>
            </div>
            <!-- Add similar fields for hypertension (checkbox), heart_disease (checkbox), 
             avg_glucose_level (number), bmi (number), ever_married (select), 
             Residence_type (select), work_type (select), smoking_status (select) -->
            <button type="submit" class="btn btn-primary">Predict Risk</button>
        </form>
    </div>
</body>

</html>