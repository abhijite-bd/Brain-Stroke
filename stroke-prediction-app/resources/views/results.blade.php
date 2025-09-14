<!DOCTYPE html>
<html>

<head>
    <title>Prediction Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Prediction Result</h2>
        <div class="alert {{ $data['risk_level'] == 'High' ? 'alert-danger' : 'alert-success' }}">
            <strong>Risk Level:</strong> {{ $data['risk_level'] }}<br>
            <strong>Probability:</strong> {{ number_format($data['risk_probability'] * 100, 2) }}%
        </div>
        <a href="/" class="btn btn-secondary">Back</a>
    </div>
</body>

</html>