<?php
// models/dojoRecorrenciaModel.php
include_once __DIR__ . "/../db/conexao.php";

class DojoRecorrenciaModel
{
    private $conexao;

    public function __construct()
    {
        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    public function buscarAtivaOuPendentePorFiliado($id_filiado)
    {
        try {
            $stmt = $this->conexao->prepare("
                SELECT * FROM dojo_recorrencias 
                WHERE id_filiado = ? AND status IN ('pendente', 'ativo', 'cancelando') 
                ORDER BY id DESC LIMIT 1
            ");
            $stmt->bind_param("i", $id_filiado);
            $stmt->execute();
            $result = $stmt->get_result();
            $dados = $result->fetch_assoc();
            $stmt->close();
            return $dados;
        } catch (Exception $e) {
            error_log("Erro ao buscar recorrencia por filiado: " . $e->getMessage());
            return null;
        }
    }

    public function proporRecorrencia($id_filiado, $valor): bool
    {
        try {
            // Cancela qualquer recorrência pendente ou ativa anterior no banco antes de propor uma nova
            $stmt = $this->conexao->prepare("
                UPDATE dojo_recorrencias 
                SET status = 'cancelado', data_cancelamento = NOW() 
                WHERE id_filiado = ? AND status IN ('pendente', 'ativo', 'cancelando')
            ");
            $stmt->bind_param("i", $id_filiado);
            $stmt->execute();
            $stmt->close();

            // Insere a nova proposta
            $stmt = $this->conexao->prepare("
                INSERT INTO dojo_recorrencias (id_filiado, valor, status)
                VALUES (?, ?, 'pendente')
            ");
            $stmt->bind_param("id", $id_filiado, $valor);
            $resultado = $stmt->execute();
            $stmt->close();
            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao propor recorrencia: " . $e->getMessage());
            return false;
        }
    }

    public function registrarAceiteRecorrencia($id_recorrencia, $ip, $versao): bool
    {
        try {
            $stmt = $this->conexao->prepare("
                UPDATE dojo_recorrencias 
                SET ip_aceite = ?, termo_versao = ?, data_aceite = NOW()
                WHERE id = ?
            ");
            $stmt->bind_param("ssi", $ip, $versao, $id_recorrencia);
            $resultado = $stmt->execute();
            $stmt->close();
            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao registrar aceite de recorrencia: " . $e->getMessage());
            return false;
        }
    }

    public function ativarRecorrenciaStripe($id_recorrencia, $customer_id, $subscription_id): bool
    {
        try {
            $stmt = $this->conexao->prepare("
                UPDATE dojo_recorrencias 
                SET stripe_customer_id = ?, stripe_subscription_id = ?, status = 'ativo'
                WHERE id = ?
            ");
            $stmt->bind_param("ssi", $customer_id, $subscription_id, $id_recorrencia);
            $resultado = $stmt->execute();
            $stmt->close();
            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao ativar recorrencia no Stripe: " . $e->getMessage());
            return false;
        }
    }

    public function agendarCancelamentoRecorrencia($id_recorrencia): bool
    {
        try {
            $stmt = $this->conexao->prepare("
                UPDATE dojo_recorrencias 
                SET status = 'cancelando', data_cancelamento = NOW()
                WHERE id = ?
            ");
            $stmt->bind_param("i", $id_recorrencia);
            $resultado = $stmt->execute();
            $stmt->close();
            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao agendar cancelamento de recorrencia: " . $e->getMessage());
            return false;
        }
    }

    public function finalizarCancelamentoRecorrencia($subscription_id): bool
    {
        try {
            $stmt = $this->conexao->prepare("
                UPDATE dojo_recorrencias 
                SET status = 'cancelado', data_cancelamento = COALESCE(data_cancelamento, NOW())
                WHERE stripe_subscription_id = ? AND status != 'cancelado'
            ");
            $stmt->bind_param("s", $subscription_id);
            $resultado = $stmt->execute();
            $stmt->close();
            return $resultado;
        } catch (Exception $e) {
            error_log("Erro ao finalizar cancelamento de recorrencia no banco: " . $e->getMessage());
            return false;
        }
    }

    public function buscarPorSubscriptionId($subscription_id)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT * FROM dojo_recorrencias WHERE stripe_subscription_id = ? AND status != 'cancelado'");
            $stmt->bind_param("s", $subscription_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $dados = $result->fetch_assoc();
            $stmt->close();
            return $dados;
        } catch (Exception $e) {
            error_log("Erro ao buscar recorrencia por subscription ID: " . $e->getMessage());
            return null;
        }
    }
}
