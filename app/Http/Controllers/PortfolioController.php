<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Mail\NewContactMessage;
use App\Models\Experience;
use App\Models\Message;
use App\Models\PersonalInfo;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PortfolioController extends Controller
{
    public function index()
    {
        $personalInfo = PersonalInfo::latest()->first() ?? new PersonalInfo();

        $experiences = Cache::remember('experiences', now()->addHours(24), function () {
            return Experience::all();
        });

        $projects = Cache::remember('projects', now()->addHours(24), function () {
            return Project::latest()->get();
        });

        $technologies = Technology::forPortfolio();

        return view('welcome', compact('personalInfo', 'experiences', 'projects', 'technologies'));
    }

    public function storeMessage(StoreMessageRequest $request)
    {
        $contactMessage = Message::create($request->validated());

        try {
            $adminEmail = config('portfolio.admin_email');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new NewContactMessage($contactMessage));
            }
        } catch (\Exception $e) {
            Log::error('Échec envoi email contact : ' . $e->getMessage());
        }

        return redirect()->to(route('home') . '#contact')->with('success', 'Message envoyé !');
    }

    public function showProject(Project $project)
    {
        $personalInfo = PersonalInfo::latest()->first() ?? new PersonalInfo();

        // Compteur de vues : une seule vue par visiteur par 24h (cookie)
        $cookieName = 'viewed_project_' . $project->id;
        if (!request()->cookie($cookieName)) {
            $project->incrementQuietly('views_count');
            cookie()->queue(cookie($cookieName, 1, 60 * 24));
        }

        return view('project-detail', compact('project', 'personalInfo'));
    }
}
