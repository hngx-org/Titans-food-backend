<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'lunch_price',
        'currency_code'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    /**
     * Users in an organization
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'org_id');
    }

    /**
     * Lunch wallet for an organization
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function wallet(): HasOne
    {
        return $this->hasOne(OrganizationLunchWallet::class, 'org_id');
    }

    /**
     * User invites sent from an organization
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invites(): HasMany
    {
        return $this->hasMany(OrganizationInvite::class, 'org_id');
    }

    protected static function booted()
    {
        static::creating(function ($organization) {
            // Check if the currency_code is empty or null, then set the default value
            if (empty($organization->currency_code)) {
                $organization->currency_code = 'NGN';
            }
        });
    }
}
