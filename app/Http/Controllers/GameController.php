<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $games = Game::with(['predictions' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }])->orderBy('match_date', 'asc')->get();

        // Group by date string Y-m-d
        $grouped = $games->groupBy(function ($game) {
            return $game->match_date->format('Y-m-d');
        });

        return response()->json($grouped);
    }
}
