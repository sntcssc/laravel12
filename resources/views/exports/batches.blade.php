<!DOCTYPE html>
<html>
<head>
    <title>Batches Report</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Batches Report</h1>
    <table>
        <thead>
            <tr>
                <th>Year</th>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($batches as $batch)
                <tr>
                    <td>{{ $batch->year }}</td>
                    <td>{{ $batch->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>