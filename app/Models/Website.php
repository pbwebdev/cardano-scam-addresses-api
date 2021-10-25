<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Website extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
    ];

    public function submission(): MorphOne
    {
        return $this->morphOne(Submission::class, 'submittable');
    }
}
