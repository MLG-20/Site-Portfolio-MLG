<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersonalInfo;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Message;
use Illuminate\Support\Facades\Mail;

class PortfolioController extends Controller
{
    public function index()
    {
        // CORRECTION : On prend la fiche la plus récente
        $personalInfo = PersonalInfo::latest()->first(); 
        
        $experiences = Experience::all();
        $projects = Project::orderBy('created_at', 'desc')->get();

        return view('welcome', compact('personalInfo', 'experiences', 'projects'));
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

        $message = Message::create($validated);

        try {
            $adminEmail = 'mlamine.gueye1@univ-thies.sn';
            if ($adminEmail) {
                // VOICI LA CORRECTION : On ajoute l'Email et le Téléphone dans le corps du texte
                Mail::raw(
                    "👤 Nouveau contact de : {$message->name}\n" .
                    "📧 Email : {$message->email}\n" .
                    "📞 Téléphone : {$message->phone}\n\n" .
                    "📝 Sujet : {$message->subject}\n" .
                    "--------------------------------------------------\n\n" .
                    "Message :\n{$message->message}",
                    
                    function ($mail) use ($adminEmail) {
                        $mail->to($adminEmail)->subject('Nouveau message Portfolio');
                    }
                );
            }
        } catch (\Exception $e) { }

        return redirect()->route('home', '#contact')->with('success', 'Message envoyé !');
    }

    public function showProject(Project $project)
    {
        // CORRECTION : On prend aussi la plus récente ici
        $personalInfo = PersonalInfo::latest()->first(); 
        
        return view('project-detail', compact('project', 'personalInfo'));
    }
}







