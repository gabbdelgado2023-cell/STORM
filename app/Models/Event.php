<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'date',
        'location',
        'budget',
        'organization_id',
        'status' // pending, approved, rejected
    ];

    protected $casts = [
        'date' => 'datetime',
        'budget' => 'decimal:2',
    ];

    /**
     * Get the organization that owns this event
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Scope for approved events only
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for pending events only
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Get formatted date for display
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->date->format('M d, Y - h:i A');
    }

    /**
     * Check if event is in the future
     */
    public function isFuture(): bool
    {
        return $this->date->isFuture();
    }

    /**
     * Get status badge color for UI
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'approved' => 'green',
            'pending' => 'yellow',
            'rejected' => 'red',
            default => 'gray'
        };
    }
}