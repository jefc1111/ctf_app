<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Submission extends Model implements Auditable
{
    use SoftDeletes;
    use AuditableTrait;
    use HasFactory;
    
    protected $fillable = [
        'name',
        'category_id',
        'team_id',
        'case_id',
        'content',
        'explanation',
        'draft',
        'decision_status',
        'decision_supporting_evidence'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(SubmissionCategory::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function case(): BelongsTo
    {
        return $this->belongsTo(CaseModel::class);
    }
}
