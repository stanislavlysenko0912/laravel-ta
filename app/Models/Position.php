<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperPosition
 */
class Position extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Position $position) {
            $position->name = ucfirst(strtolower($position->name));
        });
    }
}
