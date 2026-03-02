<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LiftRequest extends Model
{
    protected $fillable = ['group_id', 'requester_id', 'origin', 'destination', 'earliest_departure', 'latest_departure', 'status'];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function trip(): HasOne
    {
        return $this->hasOne(Trip::class);
    }
}
