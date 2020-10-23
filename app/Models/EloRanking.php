<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EloRanking extends Model
{
    protected $table = 'elo_ranking';

    protected $fillable = [
        'club_id', 'club', 'elo', 'elo_history'
    ];
}
