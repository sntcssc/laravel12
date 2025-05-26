<!DOCTYPE html>
<html>
<head>
    <title>Roles Export</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Roles List</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Permissions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
                <tr>
                    <td>{{ $role->name }}</td>
                    <td>{{ implode(', ', $role->getPermissionNames()->toArray()) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>