<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomBorrowing extends Model
{
    use HasFactory;

    protected $table = 'room_borrowings';

    protected $primaryKey = 'borrowing_id';

    // Accessor
    protected $appends = ['realtime_status'];

    protected $fillable = [
        'full_name',
        'nis',
        'class',
        'phone_number',
        'room_type',
        'borrow_date',
        'activity_description',
        'start_time',
        'end_time',
        'responsible_person',
        'status',
    ];

    public function getRealtimeStatusAttribute()
    {
        $now = Carbon::now();
        $start = Carbon::parse($this->borrow_date.' '.$this->start_time);
        $end = Carbon::parse($this->borrow_date.' '.$this->end_time);

        if ($now->lessThan($start)) {
            return 'upcoming';
        } elseif ($now->between($start, $end)) {
            return 'ongoing';
        } else {
            return 'completed';
        }
    }

    // Scope
    public function scopeOngoing(Builder $query)
    {
        $now = Carbon::now();

        return $query->whereDate('borrow_date', $now->toDateString())
            ->whereTime('start_time', '<=', $now->toTimeString())
            ->whereTime('end_time', '>=', $now->toTimeString());
    }

    public function scopeUpcoming(Builder $query)
    {
        $now = Carbon::now();

        return $query->where(function ($q) use ($now) {
            $q->whereDate('borrow_date', '>', $now->toDateString())
                ->orWhere(function ($q2) use ($now) {
                    $q2->whereDate('borrow_date', $now->toDateString())
                        ->whereTime('start_time', '>', $now->toTimeString());
                });
        });
    }

    public function scopeCompleted(Builder $query)
    {
        $now = now();

        return $query->where(function ($q) use ($now) {
            $q->whereDate('borrow_date', '<', $now->toDateString())
                ->orWhere(function ($q2) use ($now) {
                    $q2->whereDate('borrow_date', $now->toDateString())
                        ->whereTime('end_time', '<', $now->toTimeString());
                });
        });
    }
}
