@props(['project'])

<div class="projets-box" data-tags="{{ implode(',', is_array($project->tags) ? $project->tags : []) }}">
    <img src="{{ Storage::url($project->image) }}" alt="{{ $project->title }}" loading="lazy">
    <div class="projets-layer">
        <a href="{{ route('projects.show', $project) }}" class="project-title-link">
            <h4>{{ $project->title }}</h4>
        </a>
        <div class="project-links">
            @if($project->github_link)
                <a href="{{ $project->github_link }}" target="_blank" rel="noopener noreferrer" title="Voir sur GitHub">
                    <i class="fa-brands fa-github"></i>
                </a>
            @endif
            @if($project->demo_link)
                <a href="{{ $project->demo_link }}" target="_blank" rel="noopener noreferrer" title="Voir la démo">
                    <i class="fa-solid fa-up-right-from-square"></i>
                </a>
            @endif
        </div>
    </div>
</div>
