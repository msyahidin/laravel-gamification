<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use QCod\Gamify\Reputation;

class UserReputation extends Reputation
{
    use HasFactory;

    protected $table = 'reputations';

    protected $casts = [
      'meta' => 'json'
    ];

    public function eventPoint() {
        return $this->meta;
    }
}
