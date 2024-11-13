<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = ['user_id', 'promisse_id', 'type'];

    public static function toggleLike($userId, $promisseId)
    {
        $like = self::where('user_id', $userId)
                    ->where('promisse_id', $promisseId)
                    ->first();

        if ($like) {
            if ($like->type == 'like') {
                $like->delete();
            } elseif ($like->type == 'dislike') {
                $like->delete();
            }
        }

        $newType = $like && $like->type == 'like' ? 'dislike' : 'like';
        self::create([
            'user_id' => $userId,
            'promisse_id' => $promisseId,
            'type' => $newType,
        ]);
    }
}
