<?php
/**
 * Modelo de Lutas e Combates (campeonato_lutas)
 * Padrão IDÊNTICO ao site principal (models/lutaModel.php)
 */

include_once __DIR__ . "/../db/conexao.php";

class Luta {

    private $conexao;

    public function __construct() {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function buscarPorChave(int $idChave): array {
        $sql = "SELECT 
                    l.*,
                    aka.nome_atleta_snapshot AS nome_aka,
                    aka.dojo_snapshot AS dojo_aka,
                    aka.graduacao_nome_snapshot AS graduacao_aka,
                    shiro.nome_atleta_snapshot AS nome_shiro,
                    shiro.dojo_snapshot AS dojo_shiro,
                    shiro.graduacao_nome_snapshot AS graduacao_shiro,
                    venc.nome_atleta_snapshot AS nome_vencedor,
                    cat.nome AS categoria_nome
                FROM campeonato_lutas l
                LEFT JOIN campeonato_inscricoes aka ON l.id_inscricao_aka = aka.id_inscricao
                LEFT JOIN campeonato_inscricoes shiro ON l.id_inscricao_shiro = shiro.id_inscricao
                LEFT JOIN campeonato_inscricoes venc ON l.id_vencedor = venc.id_inscricao
                LEFT JOIN campeonato_categorias cat ON l.id_categoria = cat.id_categoria
                WHERE l.id_chave = ?
                ORDER BY l.rodada ASC, l.posicao_na_chave ASC";

        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("i", $idChave);
        $stmt->execute();
        $res = $stmt->get_result();
        $lista = [];
        while ($row = $res->fetch_assoc()) {
            $lista[] = $row;
        }
        $stmt->close();
        return $lista;
    }

    public function buscarPorId(int $idLuta): ?array {
        $sql = "SELECT 
                    l.*,
                    aka.nome_atleta_snapshot AS nome_aka,
                    aka.dojo_snapshot AS dojo_aka,
                    aka.graduacao_nome_snapshot AS graduacao_aka,
                    shiro.nome_atleta_snapshot AS nome_shiro,
                    shiro.dojo_snapshot AS dojo_shiro,
                    shiro.graduacao_nome_snapshot AS graduacao_shiro,
                    cat.nome AS categoria_nome,
                    venc.nome_atleta_snapshot AS nome_vencedor
                FROM campeonato_lutas l
                LEFT JOIN campeonato_inscricoes aka ON l.id_inscricao_aka = aka.id_inscricao
                LEFT JOIN campeonato_inscricoes shiro ON l.id_inscricao_shiro = shiro.id_inscricao
                LEFT JOIN campeonato_inscricoes venc ON l.id_vencedor = venc.id_inscricao
                LEFT JOIN campeonato_categorias cat ON l.id_categoria = cat.id_categoria
                WHERE l.id_luta = ?";

        $stmt = $this->conexao->prepare($sql);
        $stmt->bind_param("i", $idLuta);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function buscarPorTatame(int $idTatame, ?string $status = null): array {
        $sql = "SELECT 
                    l.*,
                    aka.nome_atleta_snapshot AS nome_aka,
                    aka.dojo_snapshot AS dojo_aka,
                    shiro.nome_atleta_snapshot AS nome_shiro,
                    shiro.dojo_snapshot AS dojo_shiro,
                    venc.nome_atleta_snapshot AS nome_vencedor,
                    cat.nome AS categoria_nome
                FROM campeonato_lutas l
                LEFT JOIN campeonato_inscricoes aka ON l.id_inscricao_aka = aka.id_inscricao
                LEFT JOIN campeonato_inscricoes shiro ON l.id_inscricao_shiro = shiro.id_inscricao
                LEFT JOIN campeonato_inscricoes venc ON l.id_vencedor = venc.id_inscricao
                LEFT JOIN campeonato_categorias cat ON l.id_categoria = cat.id_categoria
                WHERE (l.id_tatame = ? OR l.id_tatame IS NULL)";

        if ($status !== null) {
            $sql .= " AND l.status = ?";
        }

        $sql .= " ORDER BY l.numero_luta ASC";

        $stmt = $this->conexao->prepare($sql);
        if ($status !== null) {
            $stmt->bind_param("is", $idTatame, $status);
        } else {
            $stmt->bind_param("i", $idTatame);
        }

        $stmt->execute();
        $res = $stmt->get_result();
        $lista = [];
        while ($row = $res->fetch_assoc()) {
            $lista[] = $row;
        }
        $stmt->close();
        return $lista;
    }

    public function criarLuta(array $dados): int {
        $sql = "INSERT INTO campeonato_lutas (
            id_chave, id_campeonato, id_categoria, id_tatame, numero_luta,
            rodada, posicao_na_chave, id_inscricao_aka, id_inscricao_shiro,
            status, proxima_luta_id
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexao->prepare($sql);
        $idChave = (int)$dados['id_chave'];
        $idCamp = (int)$dados['id_campeonato'];
        $idCat = (int)$dados['id_categoria'];
        $idTatame = !empty($dados['id_tatame']) ? (int)$dados['id_tatame'] : null;
        $num = (int)$dados['numero_luta'];
        $rodada = (int)$dados['rodada'];
        $pos = (int)$dados['posicao_na_chave'];
        $aka = !empty($dados['id_inscricao_aka']) ? (int)$dados['id_inscricao_aka'] : null;
        $shiro = !empty($dados['id_inscricao_shiro']) ? (int)$dados['id_inscricao_shiro'] : null;
        $prox = !empty($dados['proxima_luta_id']) ? (int)$dados['proxima_luta_id'] : null;
        $status = $dados['status'] ?? 'pendente';

        $stmt->bind_param(
            "iiiiiiiiisi",
            $idChave,
            $idCamp,
            $idCat,
            $idTatame,
            $num,
            $rodada,
            $pos,
            $aka,
            $shiro,
            $status,
            $prox
        );

        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }

    public function definirVencedor(int $idLuta, int $idVencedor, string $formaVitoria): bool {
        $stmt = $this->conexao->prepare("UPDATE campeonato_lutas SET id_vencedor = ?, forma_vitoria = ?, status = 'concluida' WHERE id_luta = ?");
        $stmt->bind_param("isi", $idVencedor, $formaVitoria, $idLuta);
        $ok = $stmt->execute();
        $stmt->close();

        // Se houver próxima luta encadeada, avança o vencedor automaticamente
        $luta = $this->buscarPorId($idLuta);
        if ($luta && !empty($luta['proxima_luta_id'])) {
            $proxId = (int)$luta['proxima_luta_id'];
            $pos = (int)$luta['posicao_na_chave'];
            $campoDestino = ($pos % 2 !== 0) ? 'id_inscricao_aka' : 'id_inscricao_shiro';

            $stmtProx = $this->conexao->prepare("UPDATE campeonato_lutas SET {$campoDestino} = ? WHERE id_luta = ?");
            $stmtProx->bind_param("ii", $idVencedor, $proxId);
            $stmtProx->execute();
            $stmtProx->close();
        }

        return $ok;
    }

    public function atualizarStatus(int $idLuta, string $status): bool {
        $stmt = $this->conexao->prepare("UPDATE campeonato_lutas SET status = ? WHERE id_luta = ?");
        $stmt->bind_param("si", $status, $idLuta);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
