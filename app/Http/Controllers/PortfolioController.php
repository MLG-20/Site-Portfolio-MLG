<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use App\Models\PersonalInfo;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Message;

class PortfolioController extends Controller
{
    public function index() {
        // 1. On récupère les données
        $personalInfo = PersonalInfo::first();
        $experiences = Experience::all();
        $projects = Project::all();

        // 3. On envoie les données à la vue (Méthode explicite)
        return view('welcome', [
            'personalInfo' => $personalInfo,
            'experiences' => $experiences,
            'projects' => $projects
        ]);
    }

   public function storeMessage(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|max:100',
        'email' => 'required|email',
        'phone' => 'nullable',
        'subject' => 'required',
        'message' => 'required'
    ]);

    // On crée le message dans la base de données
    $message = Message::create($validated);

    // ---- ENVOI DE L'EMAIL DIRECT ----
    try {
        $adminEmail = config('mail.from.address');
        if ($adminEmail) {
            // On envoie un email simple avec les infos du message
            Mail::raw(
                "Nouveau message de : {$message->name} ({$message->email})\n\n" .
                "Sujet : {$message->subject}\n\n" .
                "Message :\n{$message->message}",
                function ($mail) use ($adminEmail) {
                    $mail->to($adminEmail)
                         ->subject('Nouveau message sur ton Portfolio !');
                }
            );
        }
    } catch (\Exception $e) {
        // En cas d'erreur, on ne bloque pas l'utilisateur, mais on peut logger l'erreur
        // Pour l'instant on ne fait rien, mais en production on ajouterait un log.
    }
    // ---- FIN DE L'ENVOI ----

    return redirect()->route('home', '#contact')->with('success', 'Message envoyé avec succès !');
}
}