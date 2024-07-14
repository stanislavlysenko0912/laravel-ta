<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperUser
 */
class User extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'position_id',
        'photo'
    ];

    protected $casts = [
        'registration_timestamp' => 'timestamp',
    ];

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }
}
