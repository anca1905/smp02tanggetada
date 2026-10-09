<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'cover'];

    /**
     * Get URL for subject cover if available in storage.
     */
    public function getCoverUrlAttribute(): ?string
    {
        if ($this->cover && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->cover)) {
            return asset('storage/'.$this->cover);
        }

        return null;
    }

    /**
     * Get dynamic styling theme (inline CSS gradients and icons) based on subject name.
     *
     * @return array{gradient: string, banner: string, icon: string, color: string}
     */
    public function getThemeAttribute(): array
    {
        $name = strtolower($this->name ?? '');

        if (str_contains($name, 'matematika') || str_contains($name, 'mtk') || str_contains($name, 'berhitung')) {
            return [
                'gradient' => 'linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%)',
                'banner' => 'linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 50%, #2563eb 100%)',
                'icon' => 'fas fa-calculator',
                'color' => '#2563eb',
            ];
        }

        if (str_contains($name, 'indonesia') || str_contains($name, 'inggris') || str_contains($name, 'bahasa') || str_contains($name, 'literasi')) {
            return [
                'gradient' => 'linear-gradient(135deg, #047857 0%, #10b981 100%)',
                'banner' => 'linear-gradient(135deg, #064e3b 0%, #047857 50%, #059669 100%)',
                'icon' => 'fas fa-book-open',
                'color' => '#059669',
            ];
        }

        if (str_contains($name, 'ipa') || str_contains($name, 'fisika') || str_contains($name, 'biologi') || str_contains($name, 'kimia') || str_contains($name, 'sains')) {
            return [
                'gradient' => 'linear-gradient(135deg, #7c3aed 0%, #8b5cf6 100%)',
                'banner' => 'linear-gradient(135deg, #4c1d95 0%, #6d28d9 50%, #7c3aed 100%)',
                'icon' => 'fas fa-flask',
                'color' => '#7c3aed',
            ];
        }

        if (str_contains($name, 'ips') || str_contains($name, 'sejarah') || str_contains($name, 'geografi') || str_contains($name, 'ekonomi') || str_contains($name, 'sosial')) {
            return [
                'gradient' => 'linear-gradient(135deg, #d97706 0%, #f59e0b 100%)',
                'banner' => 'linear-gradient(135deg, #78350f 0%, #b45309 50%, #d97706 100%)',
                'icon' => 'fas fa-globe-asia',
                'color' => '#d97706',
            ];
        }

        if (str_contains($name, 'agama') || str_contains($name, 'pai') || str_contains($name, 'islam') || str_contains($name, 'kristen') || str_contains($name, 'katolik') || str_contains($name, 'hindu') || str_contains($name, 'buddha')) {
            return [
                'gradient' => 'linear-gradient(135deg, #0f766e 0%, #14b8a6 100%)',
                'banner' => 'linear-gradient(135deg, #134e4a 0%, #0f766e 50%, #14b8a6 100%)',
                'icon' => 'fas fa-mosque',
                'color' => '#0f766e',
            ];
        }

        if (str_contains($name, 'pkn') || str_contains($name, 'ppkn') || str_contains($name, 'pancasila') || str_contains($name, 'kewarganegaraan')) {
            return [
                'gradient' => 'linear-gradient(135deg, #e11d48 0%, #f43f5e 100%)',
                'banner' => 'linear-gradient(135deg, #881337 0%, #be123c 50%, #e11d48 100%)',
                'icon' => 'fas fa-landmark',
                'color' => '#e11d48',
            ];
        }

        if (str_contains($name, 'penjas') || str_contains($name, 'pjok') || str_contains($name, 'olahraga') || str_contains($name, 'jasmani')) {
            return [
                'gradient' => 'linear-gradient(135deg, #ea580c 0%, #f97316 100%)',
                'banner' => 'linear-gradient(135deg, #7c2d12 0%, #c2410c 50%, #ea580c 100%)',
                'icon' => 'fas fa-running',
                'color' => '#ea580c',
            ];
        }

        if (str_contains($name, 'seni') || str_contains($name, 'budaya') || str_contains($name, 'musik') || str_contains($name, 'rupa') || str_contains($name, 'tari') || str_contains($name, 'prakarya')) {
            return [
                'gradient' => 'linear-gradient(135deg, #db2777 0%, #ec4899 100%)',
                'banner' => 'linear-gradient(135deg, #831843 0%, #be185d 50%, #db2777 100%)',
                'icon' => 'fas fa-palette',
                'color' => '#db2777',
            ];
        }

        if (str_contains($name, 'tik') || str_contains($name, 'informatika') || str_contains($name, 'komputer') || str_contains($name, 'it')) {
            return [
                'gradient' => 'linear-gradient(135deg, #0284c7 0%, #06b6d4 100%)',
                'banner' => 'linear-gradient(135deg, #0c4a6e 0%, #0369a1 50%, #0284c7 100%)',
                'icon' => 'fas fa-laptop-code',
                'color' => '#0284c7',
            ];
        }

        if (str_contains($name, 'bk') || str_contains($name, 'konseling') || str_contains($name, 'bimbingan')) {
            return [
                'gradient' => 'linear-gradient(135deg, #6d28d9 0%, #7c3aed 100%)',
                'banner' => 'linear-gradient(135deg, #4c1d95 0%, #5b21b6 50%, #6d28d9 100%)',
                'icon' => 'fas fa-comments',
                'color' => '#6d28d9',
            ];
        }

        return [
            'gradient' => 'linear-gradient(135deg, #1e40af 0%, #3b82f6 100%)',
            'banner' => 'linear-gradient(135deg, #172554 0%, #1e3a8a 50%, #1d4ed8 100%)',
            'icon' => 'fas fa-graduation-cap',
            'color' => '#1d4ed8',
        ];
    }
}
