<!DOCTYPE html>
<html>
<head>
    <title>Leaves Report</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Leaves Report</h1>
    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Programme</th>
                <th>Batch</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reason</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaves as $leave)
                <tr>
                    <td>{{ $leave->student->name }}</td>
                    <td>{{ $leave->programme->name }}</td>
                    <td>{{ $leave->batch->name }}</td>
                    <td>{{ $leave->start_date->format('Y-m-d') }}</td>
                    <td>{{ $leave->end_date->format('Y-m-d') }}</td>
                    <td>{{ $leave->reason }}</td>
                    <td>{{ $leave->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>