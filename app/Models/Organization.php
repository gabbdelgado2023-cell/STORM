<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    protected $fillable = [
        'name', 
        'description', 
        'category',
        'vision',
        'mission', 
        'officer_id',
        'approval_status',
        'approved_by',
        'approved_at',
        'rejection_reason'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Get the officer who manages this organization
     */
    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    /**
     * Get the dean who approved this organization
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get all memberships for this organization
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    /**
     * Get all approved members for this organization
     */
    public function approvedMembers()
    {
        return $this->memberships()->where('status', 'approved')->with('user');
    }

    /**
     * Get all pending memberships for this organization
     */
    public function pendingMembers()
    {
        return $this->memberships()->where('status', 'pending')->with('user');
    }

    /**
     * Get all events for this organization
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Check if organization has minimum required members (5)
     */
    public function isActive(): bool
    {
        return $this->approvedMembers()->count() >= 5;
    }

    /**
     * Get organization status based on member count
     */
    public function getStatusAttribute(): string
    {
        if ($this->approval_status !== 'approved') {
            return ucfirst($this->approval_status);
        }
        
        return $this->isActive() ? 'Active' : 'Inactive';
    }

    /**
     * Check if organization is approved by dean
     */
    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    /**
     * Scope for approved organizations only
     */
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    /**
     * Scope for pending organizations only
     */
    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    /**
     * Get approval status badge color for UI
     */
    public function getApprovalColorAttribute(): string
    {
        return match($this->approval_status) {
            'approved' => 'green',
            'pending' => 'yellow',
            'rejected' => 'red',
            default => 'gray'
        };
    }

    public function officers(): HasMany
    {
        return $this->hasMany(Officer::class);
    }
}