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
        @if ($errors->any())
            <article style="background-color: #ffcdd2; border-color: #f44336;" >
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </article>
        @endif
        <form action="{{ route('bookings.store') }}" method="POST">
            @csrf
            <label for="user_id">User ID</label>
            <input type="number" id="user_id" name="user_id" value="{{ auth()->id() }}" readonly>

            <label for="room_id">Ruangan</label>
            <select id="room_id" name="room_id" required>
                <option value="" disabled selected>-- Pilih Ruangan --</option>
                @if (!empty($rooms))
                    @foreach ($rooms as $room)
                        <option value="{{ $room['id'] }}">
                            {{ $room['name'] ?? 'Tanpa Nama' }} 
                        </option>
                    @endforeach
                @else
                    <option disabled>Gagal Memuat Ruangan</option>
                @endif
            </select>

            <div id="roomDetails" style="display:none; margin-top: 1em; margin-bottom: 1.5em; border: 1px solid #ccc; padding: 1em; border-radius: 8px; background-color: #f9f9f9;">
                <h3>Detail Ruangan</h3>
                <p><strong>Kapasitas:</strong> <span id="capacity"></span></p>
                <p><strong>Fasilitas:</strong> <span id="facilities"></span></p>
            </div>

            <div id="data-rooms" data-rooms='@json($rooms ?? [])' style="display:none;"></div>

            <label for="title">Judul</label>
            <input type="text" id="title" name="title" placeholder="Judul Booking" required>

            <label for="start_time">Waktu Mulai</label>
            <input type="datetime-local" id="start_time" name="start_time" required>

            <label for="end_time">Waktu Selesai</label>
            <input type="datetime-local" id="end_time" name="end_time" required>

            <button type="submit">Simpan</button>
        </form>
    </main>

    <script>
        const rooms = JSON.parse(document.getElementById('data-rooms').dataset.rooms);

        const roomSelect = document.getElementById('room_id');
        const roomDetails = document.getElementById('roomDetails');
        const capacitySpan = document.getElementById('capacity');
        const facilitiesSpan = document.getElementById('facilities');

        roomSelect.addEventListener('change', function() {
            const selectedId = parseInt(this.value);
            if (!selectedId) {
                roomDetails.style.display = 'none';
                capacitySpan.textContent = '';
                facilitiesSpan.textContent = '';
                return;
            }

            const selectedRoom = rooms.find((r)=> r.id === selectedId);
            if (selectedRoom) {
                capacitySpan.textContent = selectedRoom.capacity || '-';
                facilitiesSpan.textContent = selectedRoom.facilities || '-';
                roomDetails.style.display = 'block';
            } else {
                roomDetails.style.display = 'none';
                capacitySpan.textContent = '';
                facilitiesSpan.textContent = '';
            }
        });
    </script>
</body>
</html>
