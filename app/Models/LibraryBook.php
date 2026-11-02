<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LibraryBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_code',
        'title',
        'author',
        'publisher',
        'publish_year',
        'isbn',
        'category',
        'class_level',
        'total_stock',
        'available_stock',
        'cover_image',
        'location_shelf',
        'description',
    ];

    protected $casts = [
        'total_stock' => 'integer',
        'available_stock' => 'integer',
    ];

    public function loans(): HasMany
    {
        return $this->hasMany(LibraryLoan::class, 'book_id');
    }

    public function activeLoans(): HasMany
    {
        return $this->hasMany(LibraryLoan::class, 'book_id')->where('status', 'borrowed');
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'buku_paket_bos' => 'Buku Paket BOS',
            'referensi' => 'Buku Referensi',
            'fiksi' => 'Fiksi / Cerita',
            'non_fiksi' => 'Non-Fiksi',
            'majalah' => 'Majalah / Jurnal',
            default => 'Lainnya',
        };
    }
}
