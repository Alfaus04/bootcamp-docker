<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="Room",
 *     type="object",
 *     required={"id", "name", "capacity"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Meeting Room A"),
 *     @OA\Property(property="capacity", type="integer", example=10),
 *     @OA\Property(property="facilities", type="string", example="AC, Projector", nullable=true)
 * )
 *
 * @OA\Schema(
 *     schema="RoomCreate",
 *     type="object",
 *     required={"name", "capacity"},
 *     @OA\Property(property="name", type="string", example="New Meeting Room"),
 *     @OA\Property(property="capacity", type="integer", example=15),
 *     @OA\Property(property="facilities", type="string", example="AC, Whiteboard", nullable=true)
 * )
 *
 * @OA\Schema(
 *     schema="RoomUpdate",
 *     type="object",
 *     required={"capacity"},
 *     @OA\Property(property="name", type="string", example="Updated Room Name"),
 *     @OA\Property(property="capacity", type="integer", example=20),
 *     @OA\Property(property="facilities", type="string", example="AC", nullable=true)
 * )
 */
class RoomController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/rooms",
     *     summary="Get list of all rooms",
     *     tags={"Room"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Room"))
     *     )
     * )
     */
    public function index()
    {
        return Room::all();
    }

    /**
     * @OA\Post(
     *     path="/api/rooms",
     *     summary="Create a new room",
     *     tags={"Room"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RoomCreate")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Room created",
     *         @OA\JsonContent(ref="#/components/schemas/Room")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer',
            'facilities' => 'string|nullable'
        ]);

        $room = Room::create($validated);
        return response()->json($room, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/rooms/{id}",
     *     summary="Get room by ID",
     *     tags={"Room"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Room ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Room data retrieved",
     *         @OA\JsonContent(ref="#/components/schemas/Room")
     *     ),
     *     @OA\Response(response=404, description="Room not found")
     * )
     */
    public function show(Room $room)
    {
        return $room;
    }

    /**
     * @OA\Put(
     *     path="/api/rooms/{id}",
     *     summary="Update room data",
     *     tags={"Room"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Room ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RoomUpdate")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Room updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Room")
     *     ),
     *     @OA\Response(response=404, description="Room not found")
     * )
     */
    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'capacity' => 'required|integer',
            'facilities' => 'string|nullable'
        ]);

        $room->update($validated);
        return response()->json($room);
    }

    /**
     * @OA\Delete(
     *     path="/api/rooms/{id}",
     *     summary="Delete a room",
     *     tags={"Room"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Room ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Room deleted"),
     *     @OA\Response(response=404, description="Room not found")
     * )
     */
    public function destroy(Room $room)
    {
        $room->delete();
        return response()->json(null, 204);
    }
}
