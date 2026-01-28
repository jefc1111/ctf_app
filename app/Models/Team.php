<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Team extends Model implements Auditable
{
    use SoftDeletes;
    use HasFactory;
    use AuditableTrait;
    
    protected $fillable = [
        'name',
        'captain_id',
        'coach_id'
    ];

    protected static function booted()
    {
        static::saving(function ($team) {
            // Ensure captain is a team member
            if ($team->captain->team_id !== $team->id) {
                throw new \Exception('Captain must be a team member');
            }
            
            // Max 4 participants
            if ($team->members()->count() > 4) {
                throw new \Exception('Teams cannot have more than 4 members');
            }
        });
    }

    public function captain(): BelongsTo
    {
        return $this->belongsTo(User::class, 'captain_id');
    }

    public function coach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
