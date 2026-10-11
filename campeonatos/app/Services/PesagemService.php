<?php
namespace App\Services;

use App\Models\InscricaoModel;
use App\Models\PesagemModel;
use App\Models\CategoriaModel;

/**
 * Serviço de Validação e Conferência de Pesagem
 */
class PesagemService
{
    /**
     * Valida e registra a pesagem de um atleta
     */
    public static function processWeighIn(int $idInscricao, int $idCampeonato, float $pesoAferido, int $operadorId, ?string $observacoes = null): array
    {
        $inscricao = InscricaoModel::find($idInscricao);
        if (!$inscricao) {
            throw new \Exception("Inscrição #{$idInscricao} não encontrada.");
        }

        $pesoMin = $inscricao['peso_min'] !== null ? (float)$inscricao['peso_min'] : null;
        $pesoMax = $inscricao['peso_max'] !== null ? (float)$inscricao['peso_max'] : null;

        $resultado = 'aprovado';
        $diferenca = 0.00;

        if ($pesoMin !== null && $pesoAferido < $pesoMin) {
            $resultado = 'reprovado';
            $diferenca = round($pesoMin - $pesoAferido, 2);
            $motivo = "Abaixo do peso mínimo da categoria (Mínimo: {$pesoMin}kg, Aferido: {$pesoAferido}kg, Diferença: -{$diferenca}kg).";
        } elseif ($pesoMax !== null && $pesoAferido > $pesoMax) {
            $resultado = 'reprovado';
            $diferenca = round($pesoAferido - $pesoMax, 2);
            $motivo = "Acima do peso máximo da categoria (Máximo: {$pesoMax}kg, Aferido: {$pesoAferido}kg, Diferença: +{$diferenca}kg).";
        } else {
            $motivo = null;
        }

        // Grava histórico de pesagem
        $idPesagem = PesagemModel::record([
            'id_inscricao' => $idInscricao,
            'id_campeonato' => $idCampeonato,
            'peso_aferido' => $pesoAferido,
            'unidade' => 'kg',
            'id_operador' => $operadorId,
            'resultado' => $resultado,
            'diferenca_peso' => $diferenca,
            'observacoes' => $observacoes ?: $motivo
        ]);

        // Atualiza status de elegibilidade na inscrição
        $statusInscricao = ($resultado === 'aprovado') ? 'aprovada' : 'reprovada';
        InscricaoModel::updateStatus($idInscricao, $statusInscricao, $motivo);

        return [
            'id_pesagem' => $idPesagem,
            'resultado' => $resultado,
            'peso_aferido' => $pesoAferido,
            'diferenca' => $diferenca,
            'motivo' => $motivo
        ];
    }
}
