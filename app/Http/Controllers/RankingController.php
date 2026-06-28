<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RankingController extends Controller
{
    public function index()
    {
        // 1. Maior pontuação total.
        // 2. Maior quantidade de placares exatos.
        // 3. Maior quantidade de classificados corretos.
        // 4. Maior quantidade de resultados corretos.
        // 5. Menor quantidade de erros (pontos = 0 após o jogo finalizar, ou seja, errou tudo).
        // 6. Data/hora do envio do palpite (quem enviou primeiro vence o desempate).
        // Resolverei o 6 ordenando pelo ID ou created_at do User como fallback.

        $ranking = User::select('users.id', 'users.name', 'users.created_at')
            ->where('users.is_admin', false)
            ->leftJoin('predictions', 'users.id', '=', 'predictions.user_id')
            ->leftJoin('games', 'predictions.game_id', '=', 'games.id')
            ->selectRaw('COALESCE(SUM(predictions.points), 0) as total_points')
            ->selectRaw('SUM(CASE WHEN predictions.is_exact_score = 1 THEN 1 ELSE 0 END) as exact_scores')
            ->selectRaw('SUM(CASE WHEN predictions.is_correct_qualifier = 1 THEN 1 ELSE 0 END) as correct_qualifiers')
            ->selectRaw('SUM(CASE WHEN predictions.is_correct_result = 1 THEN 1 ELSE 0 END) as correct_results')
            // Erros = jogo finalizado e points = 0
            ->selectRaw('SUM(CASE WHEN games.status = "finished" AND predictions.points = 0 THEN 1 ELSE 0 END) as total_errors')
            ->groupBy('users.id', 'users.name', 'users.created_at')
            ->orderByDesc('total_points')
            ->orderByDesc('exact_scores')
            ->orderByDesc('correct_qualifiers')
            ->orderByDesc('correct_results')
            ->orderBy('total_errors')
            ->orderBy('users.created_at')
            ->get();

        // Assign position rank
        $currentRank = 1;
        $ranking->transform(function ($user, $key) use (&$currentRank) {
            $user->rank = $currentRank++;
            return $user;
        });

        return response()->json($ranking);
    }
}
