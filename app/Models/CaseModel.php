<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// `Case` is reserved in PHP
class CaseModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cases';
}
