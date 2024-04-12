<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Medical Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        img {
            width: 500px;
            height: 500px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div class="container my-3">
        <div class="text-center">
            <h2>Medical Record of {{ $recordData->patient_name }}</h2>
        </div>
        <div class="row mt-3">
            <div class="col offset-4">
                <table class="table table-borderless mt-2">
                    <tbody>
                        <tr>
                            <td class="fw-medium">Date</td>
                            <td>{{ $recordData->created_at }}</td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Patient ID</td>
                            <td>{{ $recordData->patient_id }}</td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Patient</td>
                            <td>{{ $recordData->patient_name }}</td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Doctor</td>
                            <td>{{ $recordData->doctor_name }}</td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Status</td>
                            <td>{{ $recordData->inpatient_status === 1 ? 'Inpatient' : 'Outpatient' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Disease</td>
                            <td>{{ $recordData->disease }}</td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Weight</td>
                            <td>{{ $recordData->weight }}</td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Blood Pressure</td>
                            <td>{{ $recordData->blood_pressure }}</td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Blood Glucose</td>
                            <td>{{ $recordData->blood_glucose }}</td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Heart Rate</td>
                            <td>{{ $recordData->heart_rate }}</td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Repository Rate</td>
                            <td>{{ $recordData->repository_rate }}</td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Oxygen Level</td>
                            <td>{{ $recordData->oxygen_level }}</td>
                        </tr>

                        <tr>
                            <td class="fw-medium">Temperature</td>
                            <td>{{ $recordData->temperature }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @foreach ($recordData->file_records as $file)
        <div class="d-flex mt-3 justify-content-center ">

                <img src="{{ asset('files/' . $file) }}" class="rounded" alt="">

        </div>
        @endforeach
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
