<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Booking</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
</head>
<body>
    <main class="container">
        <nav>
            <ul><li><strong>Portal Booking</strong></li></ul>
            <ul>
                <li><a href="{{ route('bookings.index') }}">Daftar Booking</a></li>
                <li><a href="{{ route('bookings.create') }}" aria-current="page">Tambah Booking</a></li>
            </ul>
        </nav>

        <h1>Tambah Booking</h1>

        <form action="{{ route('bookings.store') }}" method="POST">
            @csrf

            <label for="user_id">User ID</label>
            <input type="number" id="user_id" name="user_id" placeholder="Masukkan User ID" required>

            <label for="room_id">Ruangan</label>
            <select id="room_id" name="room_id" required>
                <option value="">-- Pilih Ruangan --</option>
                @foreach ($rooms as $room)
                    <option value="{{ $room['id'] }}">
                        {{ $room['name'] ?? 'Tanpa Nama' }} (Kapasitas: {{ $room['capacity'] ?? '-' }})
                    </option>
                @endforeach
            </select>

            <label for="title">Judul</label>
            <input type="text" id="title" name="title" placeholder="Judul Booking" required>

            <label for="start_time">Waktu Mulai</label>
            <input type="datetime-local" id="start_time" name="start_time" required>

            <label for="end_time">Waktu Selesai</label>
            <input type="datetime-local" id="end_time" name="end_time" required>

            <button type="submit">Simpan</button>
        </form>
    </main>
</body>
</html>
