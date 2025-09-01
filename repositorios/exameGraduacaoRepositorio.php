<?php
include_once __DIR__ . "/../db/conexao.php";
include_once __DIR__ .  "/../models/exameGraduacaoModel.php";
include_once __DIR__ . "/../config.php";

class ExameGraduacaoRepositorio
{
    private $conexao;

    public function __construct()
    {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function criar(ExameGraduacaoModel $exame): bool
    {
        try {
            $documento = openssl_encrypt($exame->getDocumento(), 'aes-256-cbc', ENCRYPTION_KEY, 0, ENCRYPTION_IV);

            $inserir = $this->conexao->prepare("INSERT INTO exame_graduacao (nome, documento, id_graduacao_atual, id_graduacao_pretendida, id_professor, email, dataCriacao, dataMudanca, situacao) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $inserir->bind_param("ssiiissss", $exame->getNome(), $documento, $exame->getIdGraduacaoAtual(), $exame->getIdGraduacaoPretendida(), $exame->getIdProfessor(), $exame->getEmail(), $exame->getDataCriacao(), $exame->getDataMudanca(), $exame->getSituacao());
            $resultado = $inserir->execute();
            $inserir->close();

            if (!$resultado) {
                throw new Exception("Erro ao criar o exame de graduação.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao criar o exame de graduação: " . $e->getMessage());
            return false;
        }
    }

    public function buscarPorId($id)
    {
        try {
            $busca = "SELECT * FROM exame_graduacao WHERE id = ?";
            $procura = $this->conexao->prepare($busca);
            $procura->bind_param("i", $id);
            $procura->execute();
            $result = $procura->get_result();

            if ($result->num_rows === 0) {
                return null;
            }

            $exame = $result->fetch_assoc();
            $procura->close();

            $exame['documento'] = openssl_decrypt($exame['documento'], 'aes-256-cbc', ENCRYPTION_KEY, 0, ENCRYPTION_IV);

            return $exame;
        } catch (Exception $e) {
            error_log("Erro ao buscar o exame de graduação: " . $e->getMessage());
            return null;
        }
    }

    public function listarTodos()
    {
        try {
            $busca = "SELECT e.id, e.nome, e.documento, g_atual.graduacao AS graduacao_atual, g_pretendida.graduacao AS graduacao_pretendida, u.nome AS professor, e.email, e.situacao FROM exame_graduacao e LEFT JOIN graduacoes g_atual ON e.id_graduacao_atual = g_atual.id_graduacao LEFT JOIN graduacoes g_pretendida ON e.id_graduacao_pretendida = g_pretendida.id_graduacao LEFT JOIN usuarios u ON e.id_professor = u.id_usuario";
            $procura = $this->conexao->prepare($busca);
            $procura->execute();
            $result = $procura->get_result();

            if ($result->num_rows === 0) {
                return [];
            }

            $exames = [];
            while ($exame = $result->fetch_assoc()) {
                $exame['documento'] = openssl_decrypt($exame['documento'], 'aes-256-cbc', ENCRYPTION_KEY, 0, ENCRYPTION_IV);
                $exames[] = $exame;
            }
            $procura->close();

            return $exames;
        } catch (Exception $e) {
            error_log("Erro ao buscar os exames de graduação: " . $e->getMessage());
            return [];
        }
    }

    public function editar(ExameGraduacaoModel $exame): bool
    {
        try {
            $documento = openssl_encrypt($exame->getDocumento(), 'aes-256-cbc', ENCRYPTION_KEY, 0, ENCRYPTION_IV);

            $editar = $this->conexao->prepare("UPDATE exame_graduacao SET nome = ?, documento = ?, id_graduacao_atual = ?, id_graduacao_pretendida = ?, situacao = ?, dataMudanca = ? WHERE id = ?");
            $editar->bind_param("ssiissi", $exame->getNome(), $documento, $exame->getIdGraduacaoAtual(), $exame->getIdGraduacaoPretendida(), $exame->getSituacao(), $exame->getDataMudanca(), $exame->getId());
            $resultado = $editar->execute();
            $editar->close();

            if (!$resultado) {
                throw new Exception("Erro ao editar o exame de graduação.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao editar o exame de graduação: " . $e->getMessage());
            return false;
        }
    }
}