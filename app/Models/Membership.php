<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Membership extends Model
{
    protected $fillable = ['user_id', 'organization_id', 'status'];

    /**
     * Get the user that owns this membership
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the organization this membership belongs to
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Legacy relationship for club - can be removed if not needed
     */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    /**
     * Scope for approved memberships only
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for pending memberships only
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
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