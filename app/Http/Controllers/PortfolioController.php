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
    /**
     * Affiche la page d'accueil du portfolio.
     */
    public function index()
{
    $personalInfo = PersonalInfo::first();
    
    if (!$personalInfo) {
        $personalInfo = new PersonalInfo([
            'name' => 'Mamadou Lamine Gueye',
            'titles' => ['Développeur Web', 'Data Analyst', 'Freelance'],
            'description' => 'Description par défaut...',
        ]);
    }
    
    // FORCE le format correct pour la vue
    $titles = $personalInfo->titles;
    
    // Debug
    \Log::info('Titres envoyés à la vue:', [
        'raw' => $personalInfo->getRawOriginal('titles'),
        'processed' => $titles,
        'count' => count($titles)
    ]);
    
    $experiences = Experience::all();
    $projects = Project::orderBy('created_at', 'desc')->get();

    return view('welcome', compact('personalInfo', 'experiences', 'projects'));
}
    /**
     * Enregistre un message de contact et envoie une notification.
     */
    public function storeMessage(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email',
            'phone' => 'nullable',
            'subject' => 'required',
            'message' => 'required'
        ]);

        $message = Message::create($validated);

        try {
            $adminEmail = config('mail.from.address');
            if ($adminEmail) {
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
            // On ne fait rien pour ne pas bloquer l'utilisateur
        }

        return redirect()->route('home', '#contact')->with('success', 'Message envoyé avec succès !');
    }

    /**
     * Affiche la page de détail d'un projet spécifique.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    // ---- C'EST CETTE FONCTION QU'IL FAUT AJOUTER ----
    public function showProject(Project $project)
    {
        // On récupère aussi les infos personnelles pour le titre de la page
        $personalInfo = PersonalInfo::first();

        // Laravel trouve automatiquement le bon projet grâce à l'ID dans l'URL.
        return view('project-detail', compact('project', 'personalInfo'));
        
    }
    // ---------------------------------------------------
}