<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'acronym',
        'image_url',
        'leader_id',
        'city_id',
    ];

    protected $with = [
        'city'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function getImageUrlAttribute($value): string
    {
        if ($value) {
            if (str_contains($value, 'storage')) {
                return asset($value);
            }
            return asset('storage/' . $value);
        } else {
            return asset('images/img-padrao.jpg');
        }
    }
}
