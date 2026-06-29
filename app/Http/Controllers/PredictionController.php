<?php

namespace App\Http\Controllers;

use App\Models\Prediction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PredictionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'team_a_score' => 'required|integer|min:0',
            'team_b_score' => 'required|integer|min:0',
            'qualifier_team' => 'nullable|string',
        ]);

        $game = \App\Models\Game::findOrFail($request->game_id);

        // Precisamos pegar a string bruta do banco para o Laravel não tentar converter de UTC automaticamente.
        $dateString = $game->getRawOriginal('match_date');
        $matchDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dateString, 'America/Sao_Paulo');
        $currentTime = now('America/Sao_Paulo');

        if ($currentTime->greaterThanOrEqualTo($matchDate)) {
            return response()->json(['message' => 'O jogo já começou ou já passou. Não é possível palpitar.'], 403);
        }

        $qualifier = null;
        if ($request->team_a_score > $request->team_b_score) {
            $qualifier = $game->team_a;
        } elseif ($request->team_b_score > $request->team_a_score) {
            $qualifier = $game->team_b;
        } else {
            // Draw
            if (!$request->qualifier_team) {
                return response()->json(['message' => 'Em caso de empate, você deve informar o classificado.'], 422);
            }
            $qualifier = $request->qualifier_team;
        }

        $prediction = Prediction::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'game_id' => $request->game_id,
            ],
            [
                'team_a_score' => $request->team_a_score,
                'team_b_score' => $request->team_b_score,
                'qualifier_team' => $qualifier,
            ]
        );

        return response()->json(['message' => 'Palpite salvo com sucesso!', 'prediction' => $prediction], 200);
    }
}
