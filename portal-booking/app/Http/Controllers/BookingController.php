<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::latest()->get();
        return view('bookings.index', ['bookings' => $bookings]);
    }

    public function create()
    {
        try {
            $response = Http::withToken(config('services.room.token'))
                ->timeout(15)
                ->get('http://room-service-nginx/api/rooms');

            if ($response->successful()) {
                $rooms = $response->json();
            } else {
                Log::error('Failed to fetch rooms', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                $rooms = [];
            }
        } catch (\Exception $e) {
            Log::error('Error fetching rooms: ' . $e->getMessage());
            $rooms = [];
        }

        return view('bookings.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'room_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $conflict = Booking::where('room_id', $validated['room_id'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_time', '<=', $validated['start_time'])
                        ->where('end_time', '>=', $validated['end_time']);
                    });
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors(['start_time' => 'Jadwal bentrok dengan booking lain di ruangan ini.'])->withInput();
        }

        $token = config('services.room.token');
        
        try {
            $response = Http::timeout(10)
                ->withToken($token)
                ->get("http://room-service-nginx/api/rooms/" . $validated['room_id']);

            if ($response->failed()) {
                return back()->withErrors(['room_id' => "Ruangan tidak tersedia atau tidak valid"])->withInput();
            }
        } catch (\Exception $e) {
            return back()->withErrors(['room_id' => "Gagal terhubung ke layanan ruangan"])->withInput();
        }

        Booking::create($validated);

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil disimpan');
    }


    public function edit($id)
    {
        $booking = Booking::findOrFail($id);
        return view('bookings.edit', compact('booking'));
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update($request->all());

        return redirect()->route('bookings.index')->with('success', 'Booking updated successfully.');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('bookings.index');
    }
}
