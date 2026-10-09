<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name'];

    /**
     * Get dynamic styling theme (gradient and icon) based on subject name.
     *
     * @return array{gradient: string, banner: string, icon: string}
     */
    public function getThemeAttribute(): array
    {
        $name = strtolower($this->name ?? '');

        if (str_contains($name, 'matematika') || str_contains($name, 'mtk') || str_contains($name, 'berhitung')) {
            return [
                'gradient' => 'from-blue-600 to-indigo-800',
                'banner' => 'from-blue-600 via-indigo-700 to-blue-900',
                'icon' => 'fas fa-calculator',
            ];
        }

        if (str_contains($name, 'indonesia') || str_contains($name, 'inggris') || str_contains($name, 'bahasa') || str_contains($name, 'literasi')) {
            return [
                'gradient' => 'from-emerald-600 to-teal-800',
                'banner' => 'from-emerald-600 via-teal-700 to-emerald-900',
                'icon' => 'fas fa-book-open',
            ];
        }

        if (str_contains($name, 'ipa') || str_contains($name, 'fisika') || str_contains($name, 'biologi') || str_contains($name, 'kimia') || str_contains($name, 'sains')) {
            return [
                'gradient' => 'from-purple-600 to-indigo-900',
                'banner' => 'from-purple-600 via-indigo-700 to-purple-900',
                'icon' => 'fas fa-flask',
            ];
        }

        if (str_contains($name, 'ips') || str_contains($name, 'sejarah') || str_contains($name, 'geografi') || str_contains($name, 'ekonomi') || str_contains($name, 'sosial')) {
            return [
                'gradient' => 'from-amber-500 to-orange-700',
                'banner' => 'from-amber-600 via-orange-600 to-amber-800',
                'icon' => 'fas fa-globe-asia',
            ];
        }

        if (str_contains($name, 'agama') || str_contains($name, 'pai') || str_contains($name, 'islam') || str_contains($name, 'kristen') || str_contains($name, 'katolik') || str_contains($name, 'hindu') || str_contains($name, 'buddha')) {
            return [
                'gradient' => 'from-teal-600 to-emerald-800',
                'banner' => 'from-teal-600 via-cyan-700 to-teal-900',
                'icon' => 'fas fa-mosque',
            ];
        }

        if (str_contains($name, 'pkn') || str_contains($name, 'ppkn') || str_contains($name, 'pancasila') || str_contains($name, 'kewarganegaraan')) {
            return [
                'gradient' => 'from-rose-600 to-red-800',
                'banner' => 'from-rose-600 via-red-700 to-rose-900',
                'icon' => 'fas fa-landmark',
            ];
        }

        if (str_contains($name, 'penjas') || str_contains($name, 'pjok') || str_contains($name, 'olahraga') || str_contains($name, 'jasmani')) {
            return [
                'gradient' => 'from-orange-500 to-red-600',
                'banner' => 'from-orange-500 via-amber-600 to-orange-700',
                'icon' => 'fas fa-running',
            ];
        }

        if (str_contains($name, 'seni') || str_contains($name, 'budaya') || str_contains($name, 'musik') || str_contains($name, 'rupa') || str_contains($name, 'tari') || str_contains($name, 'prakarya')) {
            return [
                'gradient' => 'from-pink-600 to-rose-700',
                'banner' => 'from-pink-600 via-rose-700 to-pink-900',
                'icon' => 'fas fa-palette',
            ];
        }

        if (str_contains($name, 'tik') || str_contains($name, 'informatika') || str_contains($name, 'komputer') || str_contains($name, 'it')) {
            return [
                'gradient' => 'from-cyan-600 to-blue-800',
                'banner' => 'from-cyan-600 via-blue-700 to-indigo-800',
                'icon' => 'fas fa-laptop-code',
            ];
        }

        if (str_contains($name, 'bk') || str_contains($name, 'konseling') || str_contains($name, 'bimbingan')) {
            return [
                'gradient' => 'from-violet-600 to-purple-800',
                'banner' => 'from-violet-600 via-purple-700 to-indigo-900',
                'icon' => 'fas fa-comments',
            ];
        }

        return [
            'gradient' => 'from-blue-600 to-indigo-800',
            'banner' => 'from-blue-600 via-indigo-700 to-blue-900',
            'icon' => 'fas fa-graduation-cap',
        ];
    }
}
