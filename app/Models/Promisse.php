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
        'area_id'
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
            if (str_contains($value, 'storage')) {
                return asset($value);
            }
            return asset('storage/' . $value);
        } else {
            return asset('null');
        }
    }
}
