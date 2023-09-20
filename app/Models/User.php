<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Withdrawal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'org_id',
        'first_name',
        'last_name',
        'profile_pic',
        'email',
        'phone',
        'password_hash',
        'is_admin',
        'lunch_credit_balance',
        'refresh_token',
        'bank_number',
        'bank_code',
        'bank_name',
        'bank_region',
        'currency',
        'currency_code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password_hash',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password_hash' => 'hashed',
    ];

    /**
     * Get all the withdrawal records for the user.
     */
    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class);
    }

    /**
     * Get the organization that the user belongs to.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'org_id');
    }

    /**
     * Get all the lunches received by the user.
     */
    public function receivedLunches(): HasMany
    {
        return $this->hasMany(Lunch::class, 'receiver_id');
    }

    /**
     * Get all the lunches sent by the user.
     */
    public function sentLunches(): HasMany
    {
        return $this->hasMany(Lunch::class, 'sender_id');
    }
}
