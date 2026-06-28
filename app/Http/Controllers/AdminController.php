<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Services\ScoreCalculatorService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $scoreService;

    public function __construct(ScoreCalculatorService $scoreService)
    {
        $this->scoreService = $scoreService;
    }

    public function setGameResult(Request $request, $id)
    {
        if (!$request->user() || !$request->user()->is_admin) {
            return response()->json(['message' => 'Acesso negado. Apenas administradores podem lançar resultados.'], 403);
        }

        $request->validate([
            'team_a_score' => 'required|integer|min:0',
            'team_b_score' => 'required|integer|min:0',
            'qualifier_team' => 'nullable|string'
        ]);

        $game = Game::findOrFail($id);

        $qualifier = null;
        if ($request->team_a_score > $request->team_b_score) {
            $qualifier = $game->team_a;
        } elseif ($request->team_b_score > $request->team_a_score) {
            $qualifier = $game->team_b;
        } else {
            if (!$request->qualifier_team) {
                return response()->json(['message' => 'Em caso de empate, você deve informar o classificado.'], 422);
            }
            $qualifier = $request->qualifier_team;
        }

        $game->update([
            'team_a_score' => $request->team_a_score,
            'team_b_score' => $request->team_b_score,
            'qualifier_team' => $qualifier,
            'status' => 'finished'
        ]);

        $this->scoreService->calculatePointsForGame($game);

        return response()->json(['message' => 'Resultado oficial salvo e pontos calculados com sucesso!', 'game' => $game]);
    }
}
