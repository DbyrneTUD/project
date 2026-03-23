<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trip extends Model
{
    protected $fillable = ['lift_request_id', 'driver_id', 'requester_id', 'status', 'accepted_at', 'confirmed_at', 'completed_at', 'cancelled_at', 'cancel_reason'];

    public function liftRequest(): BelongsTo
    {
        return $this->belongsTo(LiftRequest::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
