<?php
namespace App\Services;

/**
 * Serviço Especializado de Sorteio e Geração de Chaves Eliminatórias
 * Suporta quantidades irregulares de competidores, distribuição balanceada de Byes
 * e separação de atletas da mesma academia na primeira rodada.
 */
class ChaveamentoService
{
    /**
     * Gera a chave eliminatória completa
     */
    public static function generateBracket(array $athletes, string $format = 'eliminatoria_simples'): array
    {
        $totalAthletes = count($athletes);
        if ($totalAthletes < 2) {
            throw new \Exception("A categoria precisa de no mínimo 2 atletas aprovados para gerar chaveamento.");
        }

        // 1. Calcula a próxima potência de 2
        $powerOfTwo = 1;
        while ($powerOfTwo < $totalAthletes) {
            $powerOfTwo *= 2;
        }

        $totalRounds = (int)log($powerOfTwo, 2);
        $totalByes = $powerOfTwo - $totalAthletes;

        // 2. Embaralha atletas protegendo colegas de mesma academia
        $seededAthletes = self::distributeAthletesByDojo($athletes, $powerOfTwo, $totalByes);

        // 3. Constrói a estrutura de rodadas e confrontos
        $bracketTree = [];
        $matches = [];
        $fightNumber = 1;

        // Rodada 1
        $round1MatchesCount = $powerOfTwo / 2;
        $round1Matches = [];

        for ($i = 0; $i < $round1MatchesCount; $i++) {
            $aka = $seededAthletes[$i * 2] ?? null;
            $shiro = $seededAthletes[$i * 2 + 1] ?? null;

            // Se um dos lados for BYE, o outro avança automaticamente!
            $status = ($aka === null || $shiro === null) ? 'concluida' : 'agendada';
            $vencedor = null;
            $formaVitoria = null;

            if ($aka !== null && $shiro === null) {
                $vencedor = $aka['id_inscricao'];
                $formaVitoria = 'wo'; // Avanço por Bye
            } elseif ($shiro !== null && $aka === null) {
                $vencedor = $shiro['id_inscricao'];
                $formaVitoria = 'wo'; // Avanço por Bye
            }

            $matchData = [
                'numero_luta' => $fightNumber++,
                'rodada' => 1,
                'posicao_na_chave' => $i + 1,
                'id_inscricao_aka' => $aka ? $aka['id_inscricao'] : null,
                'aka_nome' => $aka ? $aka['nome_atleta_snapshot'] : 'BYE (Isento)',
                'aka_dojo' => $aka ? $aka['dojo_snapshot'] : '',
                'id_inscricao_shiro' => $shiro ? $shiro['id_inscricao'] : null,
                'shiro_nome' => $shiro ? $shiro['nome_atleta_snapshot'] : 'BYE (Isento)',
                'shiro_dojo' => $shiro ? $shiro['dojo_snapshot'] : '',
                'status' => $status,
                'id_vencedor' => $vencedor,
                'forma_vitoria' => $formaVitoria,
                'proxima_posicao' => (int)floor($i / 2) + 1,
            ];

            $round1Matches[] = $matchData;
            $matches[] = $matchData;
        }

        $bracketTree[1] = $round1Matches;

        // Rodadas Seguintes (Semifinais, Finais, etc.)
        for ($r = 2; $r <= $totalRounds; $r++) {
            $matchesInRound = (int)($powerOfTwo / pow(2, $r));
            $roundMatches = [];

            for ($m = 0; $m < $matchesInRound; $m++) {
                // Checa se os vencedores da rodada anterior já são conhecidos por Bye
                $prevMatchAka = $bracketTree[$r - 1][$m * 2] ?? null;
                $prevMatchShiro = $bracketTree[$r - 1][$m * 2 + 1] ?? null;

                $akaInscricaoId = ($prevMatchAka && $prevMatchAka['id_vencedor']) ? $prevMatchAka['id_vencedor'] : null;
                $shiroInscricaoId = ($prevMatchShiro && $prevMatchShiro['id_vencedor']) ? $prevMatchShiro['id_vencedor'] : null;

                $matchData = [
                    'numero_luta' => $fightNumber++,
                    'rodada' => $r,
                    'posicao_na_chave' => $m + 1,
                    'id_inscricao_aka' => $akaInscricaoId,
                    'aka_nome' => $akaInscricaoId ? self::findAthleteName($athletes, $akaInscricaoId) : 'Aguardando vencedor',
                    'aka_dojo' => '',
                    'id_inscricao_shiro' => $shiroInscricaoId,
                    'shiro_nome' => $shiroInscricaoId ? self::findAthleteName($athletes, $shiroInscricaoId) : 'Aguardando vencedor',
                    'shiro_dojo' => '',
                    'status' => 'agendada',
                    'id_vencedor' => null,
                    'forma_vitoria' => null,
                    'proxima_posicao' => (int)floor($m / 2) + 1,
                ];

                $roundMatches[] = $matchData;
                $matches[] = $matchData;
            }

            $bracketTree[$r] = $roundMatches;
        }

        return [
            'total_atletas' => $totalAthletes,
            'total_slots' => $powerOfTwo,
            'total_rodadas' => $totalRounds,
            'total_byes' => $totalByes,
            'arvore' => $bracketTree,
            'lista_lutas' => $matches
        ];
    }

    /**
     * Distribui atletas separando-os por academia e alocando Byes simetricamente
     */
    private static function distributeAthletesByDojo(array $athletes, int $totalSlots, int $totalByes): array
    {
        // Agrupa atletas por academia
        $dojos = [];
        foreach ($athletes as $a) {
            $dojo = trim($a['dojo_snapshot'] ?? 'Independente');
            $dojos[$dojo][] = $a;
        }

        // Embaralha dentro de cada dojo
        foreach ($dojos as &$list) {
            shuffle($list);
        }

        // Distribui intercalando dojos
        $shuffled = [];
        $hasMore = true;
        while ($hasMore) {
            $hasMore = false;
            foreach ($dojos as $dojo => &$list) {
                if (!empty($list)) {
                    $shuffled[] = array_shift($list);
                    $hasMore = true;
                }
            }
        }

        // Cria array de slots com Byes intercalados
        $slots = array_fill(0, $totalSlots, null);

        // Aloca os Byes preferencialmente em posições ímpares das chaves (lado Shiro dos confrontos)
        $byePositions = [];
        for ($b = 0; $b < $totalByes; $b++) {
            // Distribuição balanceada de Byes entre o topo e a base da chave
            if ($b % 2 === 0) {
                $pos = 1 + ($b * 2);
            } else {
                $pos = ($totalSlots - 1) - (($b - 1) * 2);
            }
            if ($pos < $totalSlots && $pos >= 0) {
                $byePositions[$pos] = true;
            }
        }

        $athleteIndex = 0;
        for ($i = 0; $i < $totalSlots; $i++) {
            if (isset($byePositions[$i])) {
                $slots[$i] = null; // BYE
            } else {
                if (isset($shuffled[$athleteIndex])) {
                    $slots[$i] = $shuffled[$athleteIndex++];
                }
            }
        }

        return $slots;
    }

    private static function findAthleteName(array $athletes, int $idInscricao): string
    {
        foreach ($athletes as $a) {
            if ((int)$a['id_inscricao'] === $idInscricao) {
                return $a['nome_atleta_snapshot'];
            }
        }
        return 'Atleta #' . $idInscricao;
    }
}
