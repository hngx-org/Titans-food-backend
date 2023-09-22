<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrganizationInvite extends Model
{
    use HasFactory;

    protected $table = "organization_invites";

    protected $fillable = [
        'email',
        'token',
        'ttl',
        'org_id'
    ];


    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }
}
