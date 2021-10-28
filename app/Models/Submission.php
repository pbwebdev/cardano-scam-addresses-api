<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    public const STATUS_NAMES = [
        0 => 'unmarked',
        1 => 'accepted',
        2 => 'declined',
    ];

    protected $fillable = [
        'transaction',
        'description',
        'status',
    ];

    public function getStatusAttribute($value)
    {
        return self::STATUS_NAMES[$value ?? 0];
    }
}
