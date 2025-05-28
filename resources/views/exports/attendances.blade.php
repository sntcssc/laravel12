<!DOCTYPE html>
<html>
<head>
    <title>Attendances Report</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Attendances Report</h1>
    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Programme</th>
                <th>Batch</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->student->name }}</td>
                    <td>{{ $attendance->programme->name }}</td>
                    <td>{{ $attendance->batch->name }}</td>
                    <td>{{ $attendance->date->format('Y-m-d') }}</td>
                    <td>{{ $attendance->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>