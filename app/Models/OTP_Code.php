<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OTP_Code extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'otp_codes';

    protected $casts = [
        'user_id' => 'int',
    ];

    /**
     * Get the users that owns the OTP_Code
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
