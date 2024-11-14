<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = ['user_id', 'promisse_id', 'type'];

    public static function toggleLike($userId, $promisseId, $type)
    {
        $like = self::where('user_id', $userId)
            ->where('promisse_id', $promisseId)
            ->first();

        if ($like) {
            if ($like->type === $type) {
                $like->delete();
                return;
            } else {
                $like->delete();
            }
        }

        self::create([
            'user_id' => $userId,
            'promisse_id' => $promisseId,
            'type' => $type,
        ]);
    }
}
