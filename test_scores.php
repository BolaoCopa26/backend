<?php
use App\Models\User;
use App\Models\Game;
use App\Models\Prediction;
use App\Services\ScoreCalculatorService;
use Carbon\Carbon;

// Create test user
$user = User::create(['name' => 'scorer', 'password' => bcrypt('password')]);

// Game 1 (Oitavas) - Exemplo 1
$game1 = Game::create(['team_a' => 'A', 'team_b' => 'B', 'stage' => 'Oitavas', 'match_date' => Carbon::now()->subDay()]);
Prediction::create(['user_id' => $user->id, 'game_id' => $game1->id, 'team_a_score' => 2, 'team_b_score' => 1, 'qualifier_team' => 'A']);

// Game 2 (Quartas) - Exemplo 2
$game2 = Game::create(['team_a' => 'C', 'team_b' => 'D', 'stage' => 'Quartas', 'match_date' => Carbon::now()->subDay()]);
Prediction::create(['user_id' => $user->id, 'game_id' => $game2->id, 'team_a_score' => 2, 'team_b_score' => 0, 'qualifier_team' => 'C']);

// Game 3 (16-avos) - Exemplo 3
$game3 = Game::create(['team_a' => 'Brasil', 'team_b' => 'E', 'stage' => '16-avos', 'match_date' => Carbon::now()->subDay()]);
Prediction::create(['user_id' => $user->id, 'game_id' => $game3->id, 'team_a_score' => 0, 'team_b_score' => 0, 'qualifier_team' => 'Brasil']);

// Apply results
$service = new ScoreCalculatorService();

$game1->update(['team_a_score' => 2, 'team_b_score' => 1, 'qualifier_team' => 'A', 'status' => 'finished']);
$service->calculatePointsForGame($game1);

$game2->update(['team_a_score' => 3, 'team_b_score' => 0, 'qualifier_team' => 'C', 'status' => 'finished']);
$service->calculatePointsForGame($game2);

$game3->update(['team_a_score' => 1, 'team_b_score' => 1, 'qualifier_team' => 'Brasil', 'status' => 'finished']);
$service->calculatePointsForGame($game3);

// Print results
$predictions = Prediction::all();
foreach($predictions as $p) {
    echo "Game {$p->game_id} Points: {$p->points}\n";
}
