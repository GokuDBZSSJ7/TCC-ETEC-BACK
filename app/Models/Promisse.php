<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promisse extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'candidate_id',
        'party_id',
        'expected_time',
    ];

    protected $with = [
        'users',
        'parties'
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'candidate_id');
    }

    public function parties()
    {
        return $this->belongsTo(Party::class, 'party_id');
    }
}
