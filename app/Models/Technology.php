<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Technology extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'order'     => 'integer',
    ];

    /**
     * Récupère les technos actives, groupées par catégorie, triées par order.
     * Utilisé dans le contrôleur pour alimenter la vue.
     */
    public static function forPortfolio(): \Illuminate\Support\Collection
    {
        $categoryOrder = ['Backend', 'Frontend', 'Analyse de données', 'Outils'];

        $grouped = static::where('is_active', true)
            ->orderBy('order')
            ->get()
            ->groupBy('category');

        return collect($categoryOrder)
            ->filter(fn($cat) => $grouped->has($cat))
            ->mapWithKeys(fn($cat) => [$cat => $grouped[$cat]]);
    }

    /**
     * Retourne l'URL du SVG Devicon.
     * Le slug doit être au format "nom/nom-variante", ex: "python/python-original"
     * URL générée : https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/python/python-original.svg
     */
    /**
     * Retourne l'URL de l'icône.
     * Priorité : image uploadée > SVG Devicon
     */
    public function getIconUrlAttribute(): string
    {
        if ($this->custom_image) {
            return Storage::url($this->custom_image);
        }

        if (!$this->devicon_slug || $this->devicon_slug === '_custom') {
            return '';
        }

        return "https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/{$this->devicon_slug}.svg";
    }
}
