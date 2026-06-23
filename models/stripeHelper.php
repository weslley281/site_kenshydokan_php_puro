<?php
require_once __DIR__ . '/../config.php';

class StripeHelper {
    /**
     * Cria uma Checkout Session na Stripe e retorna a URL de pagamento.
     * 
     * @param string $descricao Nome do produto / Descrição da cobrança
     * @param float $valorReais Valor total em Reais (ex: 60.00)
     * @param string $emailCliente E-mail do destinatário para pré-preenchimento
     * @return array Array contendo status (true/false), url de pagamento ou mensagem de erro
     */
    public static function criarCheckoutSession($descricao, $valorReais, $emailCliente = '') {
        $secretKey = defined('STRIPE_SECRET_KEY') ? STRIPE_SECRET_KEY : '';

        if (empty($secretKey) || $secretKey === 'sk_test_51PplaceholderSecretKey') {
            return [
                'sucesso' => false,
                'erro' => 'Chave Secreta do Stripe não configurada ou inválida no arquivo config.php.'
            ];
        }

        // Converter valor de Reais para centavos (Stripe utiliza centavos)
        $valorCentavos = intval(round($valorReais * 100));

        if ($valorCentavos <= 0) {
            return [
                'sucesso' => false,
                'erro' => 'O valor da cobrança deve ser maior do que zero.'
            ];
        }

        // Definir as URLs de redirecionamento (ajustadas para o contexto do site)
        $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
        
        $successUrl = $protocolo . $host . '/views/perfil/perfil.php?pagamento=sucesso';
        $cancelUrl = $protocolo . $host . '/views/perfil/perfil.php?pagamento=cancelado';

        // Dados da requisição
        $postFields = [
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'mode' => 'payment',
            'line_items[0][price_data][currency]' => 'brl',
            'line_items[0][price_data][product_data][name]' => $descricao,
            'line_items[0][price_data][unit_amount]' => $valorCentavos,
            'line_items[0][quantity]' => 1,
        ];

        // Pré-preenchimento opcional do e-mail do cliente
        if (!empty($emailCliente)) {
            $postFields['customer_email'] = $emailCliente;
        }

        // Executar cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/checkout/sessions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
        curl_setopt($ch, CURLOPT_USERPWD, $secretKey . ':');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded'
        ]);
        
        // Desativar verificação SSL no WAMP local para compatibilidade
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            return [
                'sucesso' => false,
                'erro' => 'Erro de conexão cURL: ' . $curlError
            ];
        }

        $dados = json_decode($response, true);

        if ($httpCode >= 200 && $httpCode < 300 && isset($dados['url'])) {
            return [
                'sucesso' => true,
                'url' => $dados['url'],
                'id_sessao' => $dados['id']
            ];
        } else {
            $msgErro = isset($dados['error']['message']) ? $dados['error']['message'] : 'Erro desconhecido da API do Stripe.';
            return [
                'sucesso' => false,
                'erro' => 'Erro da Stripe (HTTP ' . $httpCode . '): ' . $msgErro
            ];
        }
    }
}
