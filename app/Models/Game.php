<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_a',
        'team_b',
        'match_date',
        'team_a_score',
        'team_b_score',
        'qualifier_team',
        'stage',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'match_date' => 'datetime',
        ];
    }

    public function predictions()
    {
        return $this->hasMany(Prediction::class);
    }
}
