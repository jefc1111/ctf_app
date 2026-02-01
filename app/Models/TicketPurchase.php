<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Carbon\Carbon;

class TicketPurchase extends Model implements Auditable
{
    use AuditableTrait;
    use HasFactory;
 
    protected $guarded = [ 'id' ]; 

    public function claimedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'claimed_by_user_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function allocate(User $user): void
    {
        $this->claimedBy()->associate($user);
        
        $this->claimed = true;
        
        $this->claimed_at = Carbon::now();

        $this->save();
    }
}
