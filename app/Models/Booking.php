<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'staff_id',
        'booking_date',
        'start_time',
        'end_time',
        'status',
        'notes',
        'total_price',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'total_price' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class)
                    ->withPivot(['price', 'duration'])
                    ->withTimestamps();
    }
}