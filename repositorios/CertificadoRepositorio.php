<?php
include_once __DIR__ . "/../db/conexao.php";
include_once __DIR__ . "/../models/certificadoModel.php";

class CertificadoRepositorio
{
    private $conexao;

    public function __construct()
    {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function criarCertificado(CertificadoModel $certificado): bool
    {
        try {
            $inserir = $this->conexao->prepare("INSERT INTO certificados (id_usuario, id_curso, codigo_verificacao, data_emissao, caminho_arquivo) VALUES (?, ?, ?, ?, ?)");
            $inserir->bind_param("iisss", 
                $certificado->getIdUsuario(),
                $certificado->getIdCurso(),
                $certificado->getCodigoVerificacao(),
                $certificado->getDataEmissao(),
                $certificado->getCaminhoArquivo()
            );
            $resultado = $inserir->execute();
            $inserir->close();

            if (!$resultado) {
                throw new Exception("Erro ao criar o certificado.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao criar o certificado: " . $e->getMessage());
            return false;
        }
    }

    public function buscarCertificadoPorCodigo(string $codigo_verificacao)
    {
        try {
            $busca = "SELECT * FROM certificados WHERE codigo_verificacao = ?";
            $procura = $this->conexao->prepare($busca);
            $procura->bind_param("s", $codigo_verificacao);
            $procura->execute();
            $result = $procura->get_result();

            if ($result->num_rows === 0) {
                return null;
            }

            $certificado = $result->fetch_assoc();
            $procura->close();

            return $certificado;
        } catch (Exception $e) {
            error_log("Erro ao buscar certificado por código: " . $e->getMessage());
            return null;
        }
    }

    public function buscarCertificadosPorUsuario(int $id_usuario): array
    {
        try {
            $certificados = [];
            $busca = "SELECT * FROM certificados WHERE id_usuario = ?";
            $procura = $this->conexao->prepare($busca);
            $procura->bind_param("i", $id_usuario);
            $procura->execute();
            $result = $procura->get_result();

            while ($row = $result->fetch_assoc()) {
                $certificados[] = $row;
            }
            $procura->close();

            return $certificados;
        } catch (Exception $e) {
            error_log("Erro ao buscar certificados por usuário: " . $e->getMessage());
            return [];
        }
    }
}
?>