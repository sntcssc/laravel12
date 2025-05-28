<!DOCTYPE html>
<html>
<head>
    <title>Sections Report</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Sections Report</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Programme</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sections as $section)
                <tr>
                    <td>{{ $section->name }}</td>
                    <td>{{ $section->programme ? $section->programme->name : 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>