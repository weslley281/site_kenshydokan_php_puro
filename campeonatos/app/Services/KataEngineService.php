<?php
namespace App\Services;

/**
 * Motor de Avaliação da Modalidade Kata
 */
class KataEngineService
{
    /**
     * Calcula a média aritmética simples das notas (precisão de 2 casas decimais)
     */
    public static function calculateAverage(array $scores): float
    {
        if (empty($scores)) {
            return 0.0;
        }

        $sum = array_sum(array_map('floatval', $scores));
        $count = count($scores);

        return round($sum / $count, 2);
    }

    /**
     * Identifica empates na primeira colocação que exigem novo Kata
     */
    public static function checkTieBreakRequired(array $ranking): bool
    {
        if (count($ranking) < 2) {
            return false;
        }

        $firstScore = (float)$ranking[0]['media_final'];
        $secondScore = (float)$ranking[1]['media_final'];

        return abs($firstScore - $secondScore) < 0.001;
    }
}
