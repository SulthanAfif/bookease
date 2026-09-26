<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'duration',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'integer',
        'duration' => 'integer',
    ];

    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class)
                    ->withPivot(['price', 'duration'])
                    ->withTimestamps();
    }
}