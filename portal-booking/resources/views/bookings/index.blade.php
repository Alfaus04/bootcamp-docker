<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Booking Ruang Rapat</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
</head>
<body>
    <main class="container">
        <nav>
            <ul><li><strong>Portal Booking</strong></li></ul>
            <ul><li><a href="{{ route('bookings.index') }}">Daftar Booking Ruang Rapat</a></li></ul>
        </nav>
        <h1>Daftar Booking Ruang Rapat</h1>

        <figure>
            <table>
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">User ID</th>
                        <th scope="col">Room ID</th>
                        <th scope="col">Title</th>
                        <th scope="col">Start Time</th>
                        <th scope="col">End Time</th>
                        <th scope="col">Created At</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>{{ $booking->user_id }}</td>                    
                            <td>{{ $booking->room_id }}</td>                    
                            <td>{{ $booking->title }}</td>                    
                            <td>{{ $booking->start_time }}</td>                    
                            <td>{{ $booking->end_time }}</td>                   
                            <td>{{ $booking->created_at->format('d M Y') }}</td>
                            <td>
                                <form action="{{ route('bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data booking?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="secondary outline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty 
                        <tr>
                            <td colspan="8" style="text-align: center;">Belum ada data booking.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </figure>
    </main>
</body>
</html>
