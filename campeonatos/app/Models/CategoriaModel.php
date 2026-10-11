<?php
namespace App\Models;

use App\Core\Database;

/**
 * Model de Categorias do Campeonato
 */
class CategoriaModel
{
    public static function byCampeonato(int $idCampeonato, ?string $modalidade = null): array
    {
        $conn = Database::getConnection();

        if ($modalidade) {
            $stmt = $conn->prepare("
                SELECT c.*,
                       (SELECT COUNT(*) FROM campeonato_inscricoes WHERE id_categoria = c.id_categoria) AS total_inscritos,
                       (SELECT COUNT(*) FROM campeonato_chaves WHERE id_categoria = c.id_categoria) AS chave_gerada
                FROM campeonato_categorias c
                WHERE c.id_campeonato = ? AND c.modalidade = ? AND c.status = 'ativa'
                ORDER BY c.modalidade ASC, c.sexo ASC, c.idade_min ASC, c.peso_min ASC
            ");
            $stmt->bind_param("is", $idCampeonato, $modalidade);
        } else {
            $stmt = $conn->prepare("
                SELECT c.*,
                       (SELECT COUNT(*) FROM campeonato_inscricoes WHERE id_categoria = c.id_categoria) AS total_inscritos,
                       (SELECT COUNT(*) FROM campeonato_chaves WHERE id_categoria = c.id_categoria) AS chave_gerada
                FROM campeonato_categorias c
                WHERE c.id_campeonato = ? AND c.status = 'ativa'
                ORDER BY c.modalidade ASC, c.sexo ASC, c.idade_min ASC, c.peso_min ASC
            ");
            $stmt->bind_param("i", $idCampeonato);
        }

        $stmt->execute();
        $res = $stmt->get_result();
        $cats = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $cats;
    }

    public static function find(int $id): ?array
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM campeonato_categorias WHERE id_categoria = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $cat = $res->fetch_assoc();
        $stmt->close();
        return $cat ?: null;
    }

    public static function create(array $data): int
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            INSERT INTO campeonato_categorias (id_campeonato, modalidade, nome, sexo, idade_min, idade_max, peso_min, peso_max, graduacao_inicio_id, graduacao_fim_id, nivel_competitivo, exige_pesagem)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "isssiiddissi",
            $data['id_campeonato'],
            $data['modalidade'],
            $data['nome'],
            $data['sexo'],
            $data['idade_min'],
            $data['idade_max'],
            $data['peso_min'],
            $data['peso_max'],
            $data['graduacao_inicio_id'],
            $data['graduacao_fim_id'],
            $data['nivel_competitivo'],
            $data['exige_pesagem']
        );
        $stmt->execute();
        $id = $conn->insert_id;
        $stmt->close();
        return $id;
    }

    public static function delete(int $id): bool
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM campeonato_categorias WHERE id_categoria = ?");
        $stmt->bind_param("i", $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public static function createDefaultTemplate(int $idCampeonato): void
    {
        $defaults = [
            ['modalidade' => 'kumite', 'nome' => 'Kumite Infantil Misto (< 14 anos)', 'sexo' => 'misto', 'idade_min' => 6, 'idade_max' => 13, 'peso_min' => null, 'peso_max' => 50.00, 'grad_ini' => 2, 'grad_fim' => 9, 'nivel' => 'Base', 'pesagem' => 1],
            ['modalidade' => 'kumite', 'nome' => 'Kumite Adulto Masculino Leve (-70kg)', 'sexo' => 'M', 'idade_min' => 18, 'idade_max' => 35, 'peso_min' => 50.00, 'peso_max' => 70.00, 'grad_ini' => 2, 'grad_fim' => 20, 'nivel' => 'Geral', 'pesagem' => 1],
            ['modalidade' => 'kumite', 'nome' => 'Kumite Adulto Masculino Médio (-80kg)', 'sexo' => 'M', 'idade_min' => 18, 'idade_max' => 35, 'peso_min' => 70.01, 'peso_max' => 80.00, 'grad_ini' => 2, 'grad_fim' => 20, 'nivel' => 'Geral', 'pesagem' => 1],
            ['modalidade' => 'kumite', 'nome' => 'Kumite Adulto Masculino Pesado (+80kg)', 'sexo' => 'M', 'idade_min' => 18, 'idade_max' => 35, 'peso_min' => 80.01, 'peso_max' => 140.00, 'grad_ini' => 2, 'grad_fim' => 20, 'nivel' => 'Geral', 'pesagem' => 1],
            ['modalidade' => 'kumite', 'nome' => 'Kumite Adulto Feminino Geral', 'sexo' => 'F', 'idade_min' => 18, 'idade_max' => 45, 'peso_min' => null, 'peso_max' => null, 'grad_ini' => 2, 'grad_fim' => 20, 'nivel' => 'Geral', 'pesagem' => 1],
            ['modalidade' => 'kata', 'nome' => 'Kata Infantil Misto (< 14 anos)', 'sexo' => 'misto', 'idade_min' => 6, 'idade_max' => 13, 'peso_min' => null, 'peso_max' => null, 'grad_ini' => 2, 'grad_fim' => 10, 'nivel' => 'Base', 'pesagem' => 0],
            ['modalidade' => 'kata', 'nome' => 'Kata Adulto Geral (>= 14 anos)', 'sexo' => 'misto', 'idade_min' => 14, 'idade_max' => 99, 'peso_min' => null, 'peso_max' => null, 'grad_ini' => 2, 'grad_fim' => 20, 'nivel' => 'Geral', 'pesagem' => 0],
            ['modalidade' => 'tameshiwari', 'nome' => 'Tameshiwari Adulto Masculino Geral', 'sexo' => 'M', 'idade_min' => 18, 'idade_max' => 99, 'peso_min' => null, 'peso_max' => null, 'grad_ini' => 6, 'grad_fim' => 20, 'nivel' => 'Avançado', 'pesagem' => 0],
        ];

        foreach ($defaults as $d) {
            self::create([
                'id_campeonato' => $idCampeonato,
                'modalidade' => $d['modalidade'],
                'nome' => $d['nome'],
                'sexo' => $d['sexo'],
                'idade_min' => $d['idade_min'],
                'idade_max' => $d['idade_max'],
                'peso_min' => $d['peso_min'],
                'peso_max' => $d['peso_max'],
                'graduacao_inicio_id' => $d['grad_ini'],
                'graduacao_fim_id' => $d['grad_fim'],
                'nivel_competitivo' => $d['nivel'],
                'exige_pesagem' => $d['pesagem']
            ]);
        }
    }
}
