<?php

namespace App\Models;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrganizationLunchWallet extends Model
{
    use HasFactory;


    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
