<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class SubmissionCategory extends Model implements Auditable
{
    use SoftDeletes;
    use AuditableTrait;

    protected $fillable = [
        'name',
        'points'
    ];

    public function nameAndPoints(): string
    {
        return "$this->name ($this->points points)";
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }
}
