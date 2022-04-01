<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all the post's reputation.
     */
    public function reputations()
    {
        return $this->morphMany('QCod\Gamify\Reputation', 'subject');
    }
}
