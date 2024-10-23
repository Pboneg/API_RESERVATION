<?php

namespace App\Http\Controllers;

use App\Events\EventCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Diffuser une notification à la création d'un événement
    public function sendNotification(Request $request)
    {
        // Créer un message personnalisé
        $message = "Nouvel événement créé : " . $request->input('event_name');

        // Diffuser l'événement via Pusher
        broadcast(new EventCreated($message))->toOthers();

        return response()->json(['message' => 'Notification envoyée.']);
    }

    /**
     * Récupérer toutes les notifications pour l'utilisateur connecté.
     */
    public function index()
    {
        // Récupérer les notifications de l'utilisateur connecté
        $notifications = Auth::user()->notifications;

        // Séparer les notifications lues et non lues
        $unreadNotifications = Auth::user()->unreadNotifications;
        $readNotifications = Auth::user()->readNotifications;

        return response()->json([
            'unread_count' => $unreadNotifications->count(),
            'notifications' => [
                'unread' => $unreadNotifications,
                'read' => $readNotifications,
            ],
        ]);
    }

    /**
     * Marquer une notification comme lue.
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['message' => 'Notification marquée comme lue.']);
        }

        return response()->json(['message' => 'Notification non trouvée.'], 404);
    }
}
