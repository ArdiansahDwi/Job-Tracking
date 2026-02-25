<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    protected $fillable = [
        'user_id',
        'company',
        'position',
        'status',
        'applied_date',
        'notes',
        'job_url',
        'salary_min',
        'salary_max',
        'location',
    ];

    protected $casts = [
        'applied_date' => 'date',
    ];

    // Status constants
    const STATUS_WISHLIST = 'wishlist';
    const STATUS_APPLIED = 'applied';
    const STATUS_INTERVIEW = 'interview';
    const STATUS_OFFER = 'offer';
    const STATUS_REJECTED = 'rejected';

    const STATUSES = [
        self::STATUS_WISHLIST => 'Wishlist',
        self::STATUS_APPLIED => 'Applied',
        self::STATUS_INTERVIEW => 'Interview',
        self::STATUS_OFFER => 'Offer',
        self::STATUS_REJECTED => 'Rejected',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}