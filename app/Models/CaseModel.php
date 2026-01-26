<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// `Case` is reserved in PHP
class CaseModel extends Model
{
    use SoftDeletes;
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cases';

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
