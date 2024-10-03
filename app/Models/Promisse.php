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
        'political_id',
        'party_id',
        'image_url',
        'budget',
        'time',
        'like',
        'deslike',
        'approvation',
        'area_id',
        'status'
    ];

    protected $with = [
        'users',
        'parties',
        'areas'
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'political_id');
    }

    public function parties()
    {
        return $this->belongsTo(Party::class, 'party_id');
    }

    public function areas()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function getImageUrlAttribute($value): string
    {
        if ($value) {
            return asset('storage/' . $value);
        }
        return asset('null');
    }

}
