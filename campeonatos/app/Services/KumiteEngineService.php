<?php
namespace App\Services;

/**
 * Motor de Regras Oficiais de Kumite Kenshydokan Jutsu
 */
class KumiteEngineService
{
    public const TEMPO_REGULAMENTAR_SEGUNDOS = 180; // 3 minutos
    public const TEMPO_GOLDEN_SCORE_SEGUNDOS = 60;  // 1 minuto
    public const TEMPO_SOLO_MAX_SEGUNDOS = 60;      // 60 segundos Ne Waza
    public const TEMPO_PASSIVIDADE_SOLO = 10;       // 10s amarração solo
    public const TEMPO_PASSIVIDADE_CLINCH = 5;      // 5s clinch estático
    public const WAZAARI_MAX_ENCERRAMENTO = 6;      // 6 Waza-ari encerram a luta
    public const PONTOS_MAX_ENCERRAMENTO = 3.0;     // 3.0 pontos somados

    /**
     * Avalia o estado do combate e determina se houve vitória antecipada
     */
    public static function evaluateState(array $sumula): array
    {
        $wazaariAka = (int)($sumula['wazaari_aka'] ?? 0);
        $wazaariShiro = (int)($sumula['wazaari_shiro'] ?? 0);
        $pontosAka = (float)($sumula['pontos_aka'] ?? 0.0);
        $pontosShiro = (float)($sumula['pontos_shiro'] ?? 0.0);
        $hansokuAka = (bool)($sumula['hansoku_make_aka'] ?? false);
        $hansokuShiro = (bool)($sumula['hansoku_make_shiro'] ?? false);
        $isGoldenScore = (bool)($sumula['golden_score_ativo'] ?? false);

        // 1. Desclassificação Direta (Hansoku-make)
        if ($hansokuAka && !$hansokuShiro) {
            return [
                'finalizada' => true,
                'vencedor_lado' => 'shiro',
                'forma_vitoria' => 'hansoku_make',
                'motivo' => 'Desclassificação direta do adversário (Hansoku-make).'
            ];
        }
        if ($hansokuShiro && !$hansokuAka) {
            return [
                'finalizada' => true,
                'vencedor_lado' => 'aka',
                'forma_vitoria' => 'hansoku_make',
                'motivo' => 'Desclassificação direta do adversário (Hansoku-make).'
            ];
        }

        // 2. Regra dos 6 Waza-ari (3.0 pontos acumulados - Waza-ari Awasete Ippon)
        if ($wazaariAka >= self::WAZAARI_MAX_ENCERRAMENTO || $pontosAka >= self::PONTOS_MAX_ENCERRAMENTO) {
            return [
                'finalizada' => true,
                'vencedor_lado' => 'aka',
                'forma_vitoria' => 'pontos',
                'motivo' => 'Superioridade técnica absoluta (6 Waza-ari / 3.0 pontos somados).'
            ];
        }
        if ($wazaariShiro >= self::WAZAARI_MAX_ENCERRAMENTO || $pontosShiro >= self::PONTOS_MAX_ENCERRAMENTO) {
            return [
                'finalizada' => true,
                'vencedor_lado' => 'shiro',
                'forma_vitoria' => 'pontos',
                'motivo' => 'Superioridade técnica absoluta (6 Waza-ari / 3.0 pontos somados).'
            ];
        }

        // 3. Regra do Golden Score (primeiro ponto encerra a luta)
        if ($isGoldenScore) {
            if ($pontosAka > $pontosShiro) {
                return [
                    'finalizada' => true,
                    'vencedor_lado' => 'aka',
                    'forma_vitoria' => 'pontos',
                    'motivo' => 'Vitória no Golden Score (Ponto de Ouro na prorrogação).'
                ];
            }
            if ($pontosShiro > $pontosAka) {
                return [
                    'finalizada' => true,
                    'vencedor_lado' => 'shiro',
                    'forma_vitoria' => 'pontos',
                    'motivo' => 'Vitória no Golden Score (Ponto de Ouro na prorrogação).'
                ];
            }
        }

        return ['finalizada' => false];
    }

    /**
     * Aplica penalidade Chui e converte 2 Chui em 0.5 ponto (Waza-ari) para o oponente
     */
    public static function applyChui(string $infratorLado, array &$sumula): array
    {
        $oponente = ($infratorLado === 'aka') ? 'shiro' : 'aka';
        $sumula["chui_{$infratorLado}"] = (int)($sumula["chui_{$infratorLado}"] ?? 0) + 1;

        $converteu = false;
        // Cada 2 Chui acumula 0.5 ponto para o adversário
        if ($sumula["chui_{$infratorLado}"] % 2 === 0) {
            $sumula["wazaari_{$oponente}"] = (int)($sumula["wazaari_{$oponente}"] ?? 0) + 1;
            $sumula["pontos_{$oponente}"] = (float)($sumula["pontos_{$oponente}"] ?? 0.0) + 0.5;
            $converteu = true;
        }

        return [
            'chui_total' => $sumula["chui_{$infratorLado}"],
            'converteu_ponto' => $converteu,
            'pontos_oponente' => $sumula["pontos_{$oponente}"] ?? 0.0
        ];
    }
}
