<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// `Case` is reserved in PHP
class CaseModel extends Model
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cases';

    public function event()
    {
        return $this->hasOne(Event::class);
    }
}
