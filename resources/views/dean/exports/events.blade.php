<!DOCTYPE html>
<html>
<head>
    <title>Events Export</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; font-size: 12px; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <h2>Events List</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Organization</th>
                <th>Date</th>
                <th>Location</th>
                <th>Status</th>
                <th>Budget</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
            <tr>
                <td>{{ $event->name }}</td>
                <td>{{ $event->organization->name }}</td>
                <td>{{ $event->date }}</td>
                <td>{{ $event->location }}</td>
                <td>{{ ucfirst($event->status) }}</td>
                <td>{{ $event->budget }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
