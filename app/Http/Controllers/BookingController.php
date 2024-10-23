<?php

namespace App\Http\Controllers;

use App\Events\ReservationUpdated;
use App\Models\Event;
use App\Models\Booking;
use App\Http\Requests\BookingRequest;
use App\Http\Resources\BookingResource;


class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::with('user', 'event')->get();
        return BookingResource::collection($bookings);
    }

    /**
     * Display the specified resource.
     */
    public function view(int $id)
    {
        $booking = Booking::with('user', 'event')->findOrFail($id);
        return new BookingResource($booking);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookingRequest $request, int $id)
    {
        $booking = Booking::findOrFail($id);
        $event = Event::findOrFail($booking->event_id);

        // Récupérer le nombre de places déjà réservées
        $previousSeats = $booking->number_of_seats;

        // Mise à jour des places disponibles
        // Réinitialisation des places avant de procéder à la mise à jour
        $event->available_seats += $previousSeats;

        if ($event->available_seats < $request->number_of_seats) {
            return response()->json([
                'message' => 'Pas assez de places disponibles pour cet événement.'
            ], 400);
        }

        $event->available_seats -= $request->number_of_seats;
        $event->save();

        $booking->update($request->validated());

        return new BookingResource($booking);
    }


    /**
     * delete the specified resource from storage.
     */
    public function delete(int $id)
    {
        $booking = Booking::findOrFail($id);
        $event = Event::findOrFail($booking->event_id);

        // Réinitialiser le nombre de places disponibles
        $event->available_seats += $booking->number_of_seats;
        $event->save();

        // Supprimer la réservation
        $booking->delete();

        return response()->json(['message' => 'Réservation supprimée']);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(BookingRequest $request)
    {
        // Récupérer l'événement concerné
        $event = Event::findOrFail($request->event_id);

        // Vérifier si le nombre de places demandées est disponible
        if ($event->available_seats < $request->number_of_seats) {
            return response()->json([
                'message' => 'Pas assez de places disponibles pour cet événement.'
            ], 400);
        }

        // Réduire le nombre de places disponibles
        $event->available_seats -= $request->number_of_seats;
        $event->save();

         // Émettre l'événement en temps réel
        event(new ReservationUpdated($event));

        // Créer la réservation
        $booking = Booking::create([
            'user_id' => $request->user()->id,
            'event_id' => $event->id,
            'number_of_seats' => $request->number_of_seats,
        ]);
        // Retourner la réponse formatée via le BookingResource
        return response()->json(new BookingResource($booking), 201);
    }

}
