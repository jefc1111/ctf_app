<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Enums\SubmissionSubset;
use Illuminate\Support\Collection;

// `Case` is reserved in PHP
class CaseModel extends Model implements Auditable
{
    use SoftDeletes;
    use HasFactory;
    use AuditableTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cases';

    protected $guarded = ['id'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function submissions(): hasMany
    {
        return $this->hasMany(Submission::class, 'case_id');
    }

    public function teamSubmissions(): HasMany
    {
        $team = auth()->user()->team;
        
        return $this->hasMany(Submission::class, 'case_id')
            ->where('team_id', $team->id);
    }

    public function userSubmissions(): HasMany
    {
        return $this->hasMany(Submission::class, 'case_id')
            ->where('owner_id', auth()->id());
    }

    public function caseSubmissionDisplayText(SubmissionSubset $subset): string
    {
        $submissions = $this->filteredSubmissions($subset);

        $count  = $submissions->count();
        $points = $submissions->sum(fn($s) => $s->category->points);

        return $count === 0 ? 'None' : "$count ($points points)";
    }

    public function caseSubmissionDisplayColor(SubmissionSubset $subset): string
    {
        return $this->filteredSubmissions($subset)->isEmpty() ? 'danger' : 'warning';
    }

    private function filteredSubmissions(SubmissionSubset $subset): Collection
    {
        return match($subset) {
            SubmissionSubset::Total => $this->submissions,
            SubmissionSubset::Team  => $this->teamSubmissions,
            SubmissionSubset::User  => $this->userSubmissions,
        };
    }
}
