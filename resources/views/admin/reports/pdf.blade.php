<!DOCTYPE html>
<html>
<head>
    <title>{{ ucfirst($type) }} Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 5px; text-align: left; }
        h2 { margin-bottom: 20px; }
    </style>
</head>
<body>
    <h2>{{ ucfirst($type) }} Report</h2>

    @if($type === 'users' && isset($data['users']))
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['users'] as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>{{ $user->email_verified_at ? 'Verified' : 'Unverified' }}</td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($type === 'organizations' && isset($data['organizations']))
        <table>
            <thead>
                <tr>
                    <th>Organization Name</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['organizations'] as $org)
                <tr>
                    <td>{{ $org->name }}</td>
                    <td>{{ $org->category }}</td>
                    <td>{{ ucfirst($org->status) }}</td>
                    <td>{{ $org->created_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($type === 'activities' && isset($data['events']))
        <table>
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Organization</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['events'] as $event)
                <tr>
                    <td>{{ $event->name }}</td>
                    <td>{{ $event->organization->name ?? 'N/A' }}</td>
                    <td>{{ $event->date }}</td>
                    <td>{{ ucfirst($event->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
