<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction',
        'description',
        'status',
    ];

    public function getStatusAttribute($value)
    {
        $names = [
            0 => 'unmarked',
            1 => 'accepted',
            2 => 'declined',
        ];

        return $names[$value];
    }
}
