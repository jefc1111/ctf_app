<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

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

    public function cases()
    {
        return $this->hasMany(CaseModel::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}
