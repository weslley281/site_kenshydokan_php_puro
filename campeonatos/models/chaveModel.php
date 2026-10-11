<?php
/**
 * Modelo de Chaves de Eliminatória (campeonato_chaves)
 * Padrão IDÊNTICO ao site principal (models/chaveModel.php)
 */

include_once __DIR__ . "/../db/conexao.php";

class Chave {

    private $conexao;

    public function __construct() {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function buscarPorCategoria(int $idCategoria): ?array {
        $stmt = $this->conexao->prepare("SELECT * FROM campeonato_chaves WHERE id_categoria = ?");
        $stmt->bind_param("i", $idCategoria);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function salvarChave(int $idCampeonato, int $idCategoria, string $tipo, int $totalAtletas, int $totalRodadas, string $arvoreDados): int {
        $sql = "INSERT INTO campeonato_chaves (id_campeonato, id_categoria, tipo, total_atletas, total_rodadas, arvore_dados)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("iisiss", $idCampeonato, $idCategoria, $tipo, $totalAtletas, $totalRodadas, $arvoreDados);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }

    public function excluirPorCategoria(int $idCategoria): bool {
        // Exclui lutas associadas primeiro
        $stmtL = $this->conexao->prepare("DELETE FROM campeonato_lutas WHERE id_categoria = ?");
        $stmtL->bind_param("i", $idCategoria);
        $stmtL->execute();
        $stmtL->close();

        $stmt = $this->conexao->prepare("DELETE FROM campeonato_chaves WHERE id_categoria = ?");
        $stmt->bind_param("i", $idCategoria);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public static function calcularEstrutura(array $atletasAptos): array {
        $totalAtletas = count($atletasAptos);
        $bracketSize = 2;
        while ($bracketSize < $totalAtletas) {
            $bracketSize *= 2;
        }
        $totalByes = $bracketSize - $totalAtletas;
        $totalRodadas = (int)log($bracketSize, 2);

        // Agrupamento por Dojo para evitar confrontos diretos na R1
        $porDojo = [];
        foreach ($atletasAptos as $atleta) {
            $dojo = $atleta['dojo_snapshot'] ?: 'Independente';
            $porDojo[$dojo][] = $atleta;
        }

        $listaOrdenada = [];
        $continua = true;
        while ($continua) {
            $continua = false;
            foreach ($porDojo as $dojo => &$membros) {
                if (!empty($membros)) {
                    $listaOrdenada[] = array_shift($membros);
                    $continua = true;
                }
            }
        }

        $slots = [];
        $byesRestantes = $totalByes;
        for ($s = 0; $s < $bracketSize; $s++) {
            if ($s % 2 !== 0 && $byesRestantes > 0) {
                $slots[] = null;
                $byesRestantes--;
            } else {
                $slots[] = array_shift($listaOrdenada) ?: null;
            }
        }

        return [
            'bracket_size' => $bracketSize,
            'total_byes' => $totalByes,
            'total_rodadas' => $totalRodadas,
            'slots' => $slots
        ];
    }
}
