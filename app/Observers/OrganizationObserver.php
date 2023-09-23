<?php

namespace App\Observers;

use App\Models\Organization;

class OrganizationObserver
{
    //
    public function created(Organization $organization) {
        // TODO:: CREATE organization lunch wallet
        $organization->wallet()->create([
            'balance' => 10000
        ]);
    }
}
