<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;
use Carbon\Carbon;

class GameSeeder extends Seeder
{
    public function run(): void
    {
        $games = [
            // 28 de Junho (Segundas de final / 16-avos)
            $this->makeGame('África do Sul', 'Canadá', '2026-06-28 16:00:00', '16-avos'),
            
            // 29 de Junho
            $this->makeGame('Brasil', 'Japão', '2026-06-29 14:00:00', '16-avos'),
            $this->makeGame('Alemanha', 'Paraguai', '2026-06-29 17:30:00', '16-avos'),
            $this->makeGame('Holanda', 'Marrocos', '2026-06-29 22:00:00', '16-avos'),
            
            // 30 de Junho
            $this->makeGame('Costa do Marfim', 'Noruega', '2026-06-30 14:00:00', '16-avos'),
            $this->makeGame('França', 'Suécia', '2026-06-30 18:00:00', '16-avos'),
            $this->makeGame('México', 'Equador', '2026-06-30 22:00:00', '16-avos'),
            
            // 01 de Julho
            $this->makeGame('Inglaterra', 'RD Congo', '2026-07-01 13:00:00', '16-avos'),
            $this->makeGame('Bélgica', 'Senegal', '2026-07-01 17:00:00', '16-avos'),
            $this->makeGame('Estados Unidos', 'Bósnia e Herzegovina', '2026-07-01 21:00:00', '16-avos'),
            
            // 02 de Julho
            $this->makeGame('Espanha', 'Áustria', '2026-07-02 16:00:00', '16-avos'),
            $this->makeGame('Portugal', 'Croácia', '2026-07-02 20:00:00', '16-avos'),
            
            // 03 de Julho
            $this->makeGame('Suíça', 'Argélia', '2026-07-03 00:00:00', '16-avos'),
            $this->makeGame('Austrália', 'Egito', '2026-07-03 15:00:00', '16-avos'),
            $this->makeGame('Argentina', 'Cabo Verde', '2026-07-03 19:00:00', '16-avos'),
            $this->makeGame('Colômbia', 'Gana', '2026-07-03 22:30:00', '16-avos'),
            
            // 04 de Julho (Oitavas)
            $this->makeGame('W73', 'W75', '2026-07-04 14:00:00', 'Oitavas'),
            $this->makeGame('W74', 'W77', '2026-07-04 18:00:00', 'Oitavas'),
            
            // 05 de Julho (Oitavas)
            $this->makeGame('W76', 'W78', '2026-07-05 17:00:00', 'Oitavas'),
            $this->makeGame('W79', 'W80', '2026-07-05 21:00:00', 'Oitavas'),
            
            // 06 de Julho (Oitavas)
            $this->makeGame('W83', 'W84', '2026-07-06 16:00:00', 'Oitavas'),
            $this->makeGame('W81', 'W82', '2026-07-06 21:00:00', 'Oitavas'),
            
            // 07 de Julho (Oitavas)
            $this->makeGame('W86', 'W88', '2026-07-07 13:00:00', 'Oitavas'),
            $this->makeGame('W85', 'W87', '2026-07-07 17:00:00', 'Oitavas'),
            
            // 09 de Julho (Quartas)
            $this->makeGame('W89', 'W90', '2026-07-09 17:00:00', 'Quartas'),
            
            // 10 de Julho (Quartas)
            $this->makeGame('W93', 'W94', '2026-07-10 16:00:00', 'Quartas'),
            
            // 11 de Julho (Quartas)
            $this->makeGame('W91', 'W92', '2026-07-11 18:00:00', 'Quartas'),
            $this->makeGame('W95', 'W96', '2026-07-11 22:00:00', 'Quartas'),
            
            // 14 de Julho (Semifinal)
            $this->makeGame('W97', 'W98', '2026-07-14 16:00:00', 'Semifinal'),
            
            // 15 de Julho (Semifinal)
            $this->makeGame('W99', 'W100', '2026-07-15 16:00:00', 'Semifinal'),
            
            // 18 de Julho (Terceiro Lugar)
            $this->makeGame('RU101', 'RU102', '2026-07-18 18:00:00', '3º Lugar'),
            
            // 19 de Julho (Final)
            $this->makeGame('W101', 'W102', '2026-07-19 16:00:00', 'Final'),
        ];

        foreach ($games as $game) {
            Game::create($game);
        }
    }

    private function makeGame($teamA, $teamB, $dateStr, $stage)
    {
        return [
            'team_a' => $teamA,
            'team_b' => $teamB,
            'match_date' => Carbon::parse($dateStr),
            'stage' => $stage
        ];
    }
}
