<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

        

        /**
         * Get all memberships for this user
         */
        public function memberships()
        {
            return $this->hasMany(Membership::class);
        }

        /**
         * Get all organizations this user manages as an officer
         */
        public function managedOrganizations()
        {
            return $this->hasMany(Organization::class, 'officer_id');
        }

        /**
         * Get all organizations this user approved as dean
         */
        public function approvedOrganizations()
        {
            return $this->hasMany(Organization::class, 'approved_by');
        }

        /**
         * Check if user is a member of a specific organization
         */
        public function isMemberOf($organizationId): bool
        {
            return $this->memberships()
                        ->where('organization_id', $organizationId)
                        ->where('status', 'approved')
                        ->exists();
        }

        /**
         * Get user's membership status in a specific organization
         */
        public function getMembershipStatus($organizationId): ?string
        {
            $membership = $this->memberships()
                            ->where('organization_id', $organizationId)
                            ->first();
            
            return $membership ? $membership->status : null;
        }
}
