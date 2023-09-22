<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Auth::viaRequest('jwt', function (Request $request) {
            try{
                $tokenPayload = JWT::decode($request->bearerToken() ?? "", new Key(config('jwt.key'), 'HS256'));

                if (isset($tokenPayload->user_id) and time() < $tokenPayload->expiry_date) {
                    // Find and return the user based on the 'user_id' from the payload
                    return User::find($tokenPayload->user_id);
                } else {
                    // If 'user_id' is missing from the payload, return null (authentication failed)
                    return null;
                }
            } catch(\Exception $e){
                Log::error($e);
                return null;
            }
        });
    }
}
