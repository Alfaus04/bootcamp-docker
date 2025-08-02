<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Booking</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
  <style>
    .form-wrapper {
      max-width: 600px;
      margin: 2rem auto;
    }
  </style>
</head>
<body>
  <main class="container">
    <nav>
      <ul><li><strong>Portal Booking</strong></li></ul>
      <ul><li><a href="{{ route('bookings.index') }}">Kembali ke Daftar Booking</a></li></ul>
    </nav>

    <h1>Edit Booking</h1>

    <section class="form-wrapper">
      <form action="{{ route('bookings.update', $booking->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="user_id">User ID</label>
        <input type="number" id="user_id" name="user_id" value="{{ $booking->user_id }}" required>

        <label for="room_id">Room ID</label>
        <input type="number" id="room_id" name="room_id" value="{{ $booking->room_id }}" required>

        <label for="title">Judul</label>
        <input type="text" id="title" name="title" value="{{ $booking->title }}" required>

        <label for="start_time">Waktu Mulai</label>
        <input type="datetime-local" id="start_time" name="start_time"
               value="{{ \Carbon\Carbon::parse($booking->start_time)->format('Y-m-d\TH:i') }}" required>

        <label for="end_time">Waktu Selesai</label>
        <input type="datetime-local" id="end_time" name="end_time"
               value="{{ \Carbon\Carbon::parse($booking->end_time)->format('Y-m-d\TH:i') }}" required>

        <button type="submit">Perbarui</button>
      </form>
    </section>
  </main>
</body>
</html>
