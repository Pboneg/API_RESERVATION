<?php

namespace App\Http\Controllers;

use App\Events\EventCreated;
use App\Models\Event;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Models\User;
use App\Notifications\EventCreatedNotification;


class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::all();
        return EventResource::collection($events);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(EventRequest $request)
    {
        // Vérifiez si une image est fournie
        $data = $request->except('image'); // Récupérez toutes les données sauf 'image'

        if ($request->hasFile('image')) {
            // Si une image est fournie, stockez-la
            $data['image'] = $request->image->store('images', 'public');
        }

        // Créez l'événement
        $event = Event::create($data);

        // Envoyer la notification aux utilisateurs avec statut = 0
        $users = User::where('status', 0)->get();
        foreach ($users as $user) {
            $user->notify(new EventCreatedNotification($event));
        }

        event(new EventCreated($event)); // Émettre l'événement WebSocket

        // Charger la relation 'user' si nécessaire
        $event->load('user');

        return response()->json(new EventResource($event), 201);
    }

    /**
     * Display the specified resource.
     */
    public function view(int $id)
    {
        $event = Event::findOrFail($id);
        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventRequest $request, int $id)
    {
        $event = Event::findOrFail($id);
        $event->update($request->only($request));

        return response()->json(new EventResource($event));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(int $id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        return response()->json(['message'=> 'Evènement supprimé avec succès'], 204);
    }
}
