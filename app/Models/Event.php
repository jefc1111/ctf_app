<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use Carbon\Carbon;

class Event extends Model implements Auditable
{
    use SoftDeletes;
    use HasFactory;
    use AuditableTrait;
    
    protected $fillable = [
        'name',
        'start_time',
        'end_time'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_time' => 'datetime', // 'datetime:Y-m-d'
            'end_time' => 'datetime',
        ];
    }

    public function cases(): HasMany
    {
        return $this->hasMany(CaseModel::class);
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function isPending(): bool
    {
        return $this->start_time > Carbon::now();
    }

    public function isInProgress(): bool
    {
        return (! $this->isPending()) && (! $this->isEnded());
    }

    public function isEnded(): bool
    {
        return $this->end_time < Carbon::now();
    }

    public function progressStatusText(): string
    {
        if ($this->isPending()) {
            return 'Pending';
        }

        if ($this->isInProgress()) {
            return 'In Progress';
        }

        return 'Complete';
    }

    public function countdownTimerLabel(): string
    {
        if ($this->isPending()) {
            return 'Starts in';
        }

        if ($this->isInProgress()) {
            return 'Ends in';
        }

        return 'Event complete';
    }
}
