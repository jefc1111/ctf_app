<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

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

    public function caseSubmissionText(): string
    {
        $submission_count = $this->submissions->count();

        $points = $this->submissions->sum(fn($s) => $s->category->points);

        return $submission_count === 0 ? 'None' : "$submission_count ($points points)";
    }

    public function caseSubmissionColor(): string
    {
        return $this->submissions->isEmpty() ? 'danger' : 'warning';
    }
}
