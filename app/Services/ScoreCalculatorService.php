<?php

namespace App\Services;

use App\Models\Prediction;
use App\Models\Game;

class ScoreCalculatorService
{
    public function calculatePointsForGame(Game $game)
    {
        if ($game->status !== 'finished') {
            return;
        }

        $predictions = Prediction::where('game_id', $game->id)->get();

        $multiplier = $this->getMultiplier($game->stage);

        foreach ($predictions as $prediction) {
            $basePoints = 0;
            $isExactScore = false;
            $isCorrectResult = false;
            $isCorrectQualifier = false;

            // 1. Placar Exato vs Resultado Correto
            if ($prediction->team_a_score === $game->team_a_score && 
                $prediction->team_b_score === $game->team_b_score) {
                // Acertou na mosca
                $basePoints += 10;
                $isExactScore = true;
                $isCorrectResult = true; // Por definição, acertou o resultado também
            } else {
                // Verifica apenas resultado
                $predResult = $this->getResultType($prediction->team_a_score, $prediction->team_b_score);
                $actualResult = $this->getResultType($game->team_a_score, $game->team_b_score);

                if ($predResult === $actualResult) {
                    $basePoints += 5;
                    $isCorrectResult = true;
                }
            }

            // 2. Classificado Correto
            if ($prediction->qualifier_team === $game->qualifier_team) {
                $basePoints += 4;
                $isCorrectQualifier = true;
            }

            $totalPoints = $basePoints * $multiplier;

            $prediction->update([
                'points' => $totalPoints,
                'is_exact_score' => $isExactScore,
                'is_correct_result' => $isCorrectResult,
                'is_correct_qualifier' => $isCorrectQualifier,
            ]);
        }
    }

    private function getResultType($scoreA, $scoreB)
    {
        if ($scoreA > $scoreB) return 'A';
        if ($scoreB > $scoreA) return 'B';
        return 'DRAW';
    }

    private function getMultiplier($stage)
    {
        return match($stage) {
            '16-avos' => 1.0,
            'Oitavas' => 1.2,
            'Quartas' => 1.5,
            'Semifinal' => 2.0,
            '3º Lugar' => 2.0, // Assuming 3rd place is same weight as semifinal
            'Final' => 3.0,
            default => 1.0,
        };
    }
}
