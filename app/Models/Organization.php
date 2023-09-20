<?php

namespace App\Models;

use App\Models\OrganizationInvite;
use App\Models\OrganizationLunchWallet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organization extends Model
{
    use HasFactory;

    public function organizationlunchwallet(): HasOne
    {
        return $this->hasOne(OrganizationLunchWallet::class, 'org_id');
    }
    
    public function organizationinvite (): HasMany
    {
        return $this->hasMany(OrganizationInvite::class, 'org_id');
    }
}
