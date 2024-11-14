<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'comment';

    protected $fillable = [
        'description',
        'image_url',
        'promisse_id',
        'user_id'
    ];

    protected $with = [
        'user'
    ];

    public function proposal()
    {
        return $this->belongsTo(Promisse::class, 'promisse_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
