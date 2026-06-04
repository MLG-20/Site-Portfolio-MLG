<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageNotificationController extends Controller
{
    /**
     * Vérifier s'il y a de nouveaux messages non lus
     * Endpoint pour la PWA
     */
    public function checkNewMessages()
    {
        // Endpoint réservé à l'admin connecté : sans cela, n'importe qui pourrait
        // lire le nom/email/sujet des messages des visiteurs (fuite de données).
        if (! Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Vérifier s'il y a des messages non lus
        $unreadCount = Message::whereNull('read_at')->count();

        // Récupérer le dernier message non lu
        $lastMessage = Message::whereNull('read_at')->latest()->first();

        return response()->json([
            'hasNewMessages' => $unreadCount > 0,
            'unreadCount' => $unreadCount,
            'lastMessage' => $lastMessage ? [
                'id' => $lastMessage->id,
                'name' => $lastMessage->name,
                'email' => $lastMessage->email,
                'subject' => $lastMessage->subject,
                'created_at' => $lastMessage->created_at,
            ] : null,
        ]);
    }
}
