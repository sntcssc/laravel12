<!DOCTYPE html>
<html>
<head>
    <title>Alumni Report</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Alumni Report</h1>
    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Programme</th>
                <th>Batch</th>
                <th>Completion Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($alumni as $alumni)
                <tr>
                    <td>{{ $alumni->student->name }}</td>
                    <td>{{ $alumni->programme->name }}</td>
                    <td>{{ $alumni->batch->name }}</td>
                    <td>{{ $alumni->completion_date->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>