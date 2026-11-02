<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LibraryLoan extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'student_id',
        'loan_code',
        'loan_date',
        'due_date',
        'return_date',
        'status',
        'fine_amount',
        'officer_name',
        'notes',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'fine_amount' => 'decimal:2',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(LibraryBook::class, 'book_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function isOverdue(): bool
    {
        if ($this->status !== 'borrowed') {
            return false;
        }

        return $this->due_date->lt(Carbon::today());
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->status === 'borrowed' && $this->isOverdue()) {
            return 'Terlambat';
        }

        return match ($this->status) {
            'borrowed' => 'Dipinjam',
            'returned' => 'Dikembalikan',
            'lost' => 'Hilang',
            'damaged' => 'Rusak',
            default => $this->status,
        };
    }
}
