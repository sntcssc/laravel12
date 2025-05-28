<!DOCTYPE html>
<html>
<head>
    <title>Enrollments Report</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Enrollments Report</h1>
    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Programme</th>
                <th>Batch</th>
                <th>Section</th>
                <th>Enrolled At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($enrollments as $enrollment)
                <tr>
                    <td>{{ $enrollment->student->name }}</td>
                    <td>{{ $enrollment->programme->name }}</td>
                    <td>{{ $enrollment->batch->name }}</td>
                    <td>{{ $enrollment->section ? $enrollment->section->name : 'N/A' }}</td>
                    <td>{{ $enrollment->enrolled_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>