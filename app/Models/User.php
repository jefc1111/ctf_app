<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Hasone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Team;
use App\Models\Event;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class User extends Authenticatable implements Auditable//, MustVerifyEmail
{
    use HasRoles;

    use AuditableTrait;

    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'team_id'
    ];

    /*
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
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

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function captainedTeam(): HasOne
    {
        return $this->hasOne(Team::class, 'captain_id');
    }

    public function coachedTeams(): HasMany
    {
        return $this->hasMany(Team::class, 'coach_id');
    }

    public function ticketPurchases(): HasMany
    {
        return $this->hasMany(TicketPurchase::class, 'claimed_by_user_id');
    }

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mentees(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function activeEvent(): ?Event
    {
        // For an admin user we may just want to send whataver the next event is  
        
        return $this->ticketPurchases
            ->filter(fn($tp) => $tp->event_id === $this->team?->event_id)
            ->map(fn($tp) => $tp->event)
            ->sortBy('start_time')
            ->last();
    }

    public function isCaptain(): bool
    {
        return $this->captainedTeam && $this->captainedTeam->id === $this->team->id;
    }

    public function inTeamForTicketPurchase(TicketPurchase $ticketPurchase): bool
    {
        if ($ticketPurchase->claimedBy->id !== $this->id) {
            return false;
        }

        return $this->team && $this->team->event_id === $ticketPurchase->event->id;
    }
}
