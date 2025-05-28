<!DOCTYPE html>
<html>
<head>
    <title>Programmes Report</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Programmes Report</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($programmes as $programme)
                <tr>
                    <td>{{ $programme->name }}</td>
                    <td>{{ $programme->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>