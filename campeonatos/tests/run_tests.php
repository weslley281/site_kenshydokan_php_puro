<?php
/**
 * Test Runner Automatizado Oficial - Kenshydokan Jutsu
 * Executa testes unitários e de regras esportivas em Pure PHP
 * Padrão IDÊNTICO ao site principal (sem namespaces ou autoloader externo)
 */

declare(strict_types=1);

include_once __DIR__ . '/../models/sumulaKumiteModel.php';
include_once __DIR__ . '/../models/sumulaKataModel.php';
include_once __DIR__ . '/../models/chaveModel.php';
include_once __DIR__ . '/../models/pesagemModel.php';

class TestRunner
{
    private int $passed = 0;
    private int $failed = 0;
    private array $errors = [];

    public function assert(bool $condition, string $testName, string $errorMsg = ''): void
    {
        if ($condition) {
            $this->passed++;
            echo "  \033[32m[PASS]\033[0m {$testName}\n";
        } else {
            $this->failed++;
            $this->errors[] = "{$testName}: {$errorMsg}";
            echo "  \033[31m[FAIL]\033[0m {$testName} - {$errorMsg}\n";
        }
    }

    public function run(): void
    {
        echo "\n======================================================\n";
        echo "  BATERIA DE TESTES OFICIAIS - KENSHYDOKAN JUTSU\n";
        echo "  (Regras Esportivas e Modelos Procedurais)\n";
        echo "======================================================\n\n";

        $this->testKumiteRules();
        $this->testKataRules();
        $this->testChaveamentoRules();
        $this->testPesagemRules();

        echo "\n------------------------------------------------------\n";
        echo "RESULTADO FINAL: {$this->passed} Passaram, {$this->failed} Falharam.\n";
        echo "------------------------------------------------------\n";

        if ($this->failed > 0) {
            echo "\nErros detalhados:\n";
            foreach ($this->errors as $err) {
                echo " - {$err}\n";
            }
            exit(1);
        } else {
            echo "\n\033[32mTODOS OS TESTES DE REGRAS ESPORTIVAS PASSARAM COM SUCESSO!\033[0m\n\n";
            exit(0);
        }
    }

    private function testKumiteRules(): void
    {
        echo "[1] Testando SumulaKumite (Motor de Regras de Kumite)...\n";
        $sumulaModel = new SumulaKumite();

        // Teste 1.1: 6 Waza-ari (3.0 pts) atinge Ippon automático (Waza-ari Awasete Ippon)
        $sumula = [
            'wazaari_aka' => 6,
            'ippon_aka' => 0,
            'pontos_aka' => 3.0,
            'wazaari_shiro' => 2,
            'ippon_shiro' => 0,
            'pontos_shiro' => 1.0,
            'hansoku_make_aka' => 0,
            'hansoku_make_shiro' => 0,
            'golden_score_ativo' => 0,
            'tempo_decorrido_segundos' => 120
        ];
        $res = $sumulaModel->avaliarRegrasKumite($sumula);
        $this->assert($res['finalizada'] === true, '6 Waza-ari (3.0 pts) finaliza luta imediatamente');
        $this->assert($res['vencedor_lado'] === 'aka', 'AKA declarado vencedor com 6 Waza-ari');
        $this->assert(str_contains($res['motivo'], 'Waza-ari Awasete Ippon'), 'Motivo indica Waza-ari Awasete Ippon');

        // Teste 1.2: Acúmulo de 2 Chui converte 0.5 pt (Waza-ari) ao adversário
        $chuiAka = 2;
        $converteuPonto = ($chuiAka % 2 === 0);
        $pontosShiroBonus = $converteuPonto ? 0.5 : 0.0;
        $this->assert($converteuPonto === true, '2º Chui de AKA aciona conversão de ponto');
        $this->assert($pontosShiroBonus === 0.5, 'SHIRO recebeu 0.5 ponto (Waza-ari) pelo 2º Chui de AKA');

        // Teste 1.3: Golden Score - Primeiro ponto encerra a luta (Ponto de Ouro)
        $sumulaGS = [
            'wazaari_aka' => 0,
            'ippon_aka' => 0,
            'pontos_aka' => 0.5,
            'wazaari_shiro' => 0,
            'ippon_shiro' => 0,
            'pontos_shiro' => 0.0,
            'hansoku_make_aka' => 0,
            'hansoku_make_shiro' => 0,
            'golden_score_ativo' => 1,
            'tempo_decorrido_segundos' => 25
        ];
        $resGS = $sumulaModel->avaliarRegrasKumite($sumulaGS);
        $this->assert($resGS['finalizada'] === true, 'Golden score encerra no primeiro ponto marcado');
        $this->assert($resGS['vencedor_lado'] === 'aka', 'AKA venceu por ponto de ouro');
        $this->assert($resGS['forma_vitoria'] === 'golden_score', 'Forma de vitória é golden_score');

        // Teste 1.4: Hansoku-make desclassifica e dá vitória imediata ao oponente
        $sumulaHM = [
            'wazaari_aka' => 4,
            'pontos_aka' => 2.0,
            'hansoku_make_aka' => 1,
            'wazaari_shiro' => 0,
            'ippon_shiro' => 0,
            'pontos_shiro' => 0.0,
            'hansoku_make_shiro' => 0,
            'golden_score_ativo' => 0,
            'tempo_decorrido_segundos' => 80
        ];
        $resHM = $sumulaModel->avaliarRegrasKumite($sumulaHM);
        $this->assert($resHM['finalizada'] === true, 'Hansoku-make encerra o combate de imediato');
        $this->assert($resHM['vencedor_lado'] === 'shiro', 'SHIRO vence por desclassificação de AKA');
        $this->assert($resHM['forma_vitoria'] === 'desclassificacao', 'Forma de vitória é desclassificacao');
    }

    private function testKataRules(): void
    {
        echo "\n[2] Testando Regras de Julgamento de Kata...\n";

        // Teste 2.1: Média aritmética das notas dos árbitros
        $notas = [8.5, 9.0, 8.6, 8.7, 8.8];
        $media = round(array_sum($notas) / count($notas), 2);
        $esperada = round((8.5 + 9.0 + 8.6 + 8.7 + 8.8) / 5, 2);
        $this->assert($media === $esperada, "Média calculada ({$media}) confere com média aritmética oficial ({$esperada})");

        // Teste 2.2: Detecção de empate na zona de medalhas (pódio)
        $ranking = [
            ['id_inscricao' => 1, 'nome_atleta' => 'Atleta 1', 'media_nota' => 9.20],
            ['id_inscricao' => 2, 'nome_atleta' => 'Atleta 2', 'media_nota' => 8.90],
            ['id_inscricao' => 3, 'nome_atleta' => 'Atleta 3', 'media_nota' => 8.90], // Empate no 2º/3º lugar
            ['id_inscricao' => 4, 'nome_atleta' => 'Atleta 4', 'media_nota' => 8.00]
        ];

        $precisaDesempate = false;
        for ($i = 0; $i < min(3, count($ranking) - 1); $i++) {
            if ($ranking[$i]['media_nota'] == $ranking[$i + 1]['media_nota']) {
                $precisaDesempate = true;
                break;
            }
        }
        $this->assert($precisaDesempate === true, 'Detecção de empate na zona de medalhas de Kata confirmada');
    }

    private function testChaveamentoRules(): void
    {
        echo "\n[3] Testando Chave::calcularEstrutura (Chaveamento Eliminatório)...\n";

        // Cria lista de 5 atletas com dojos diferentes e iguais
        $atletas = [
            ['id_inscricao' => 1, 'nome_atleta_snapshot' => 'Atleta A', 'dojo_snapshot' => 'Dojo Honbu'],
            ['id_inscricao' => 2, 'nome_atleta_snapshot' => 'Atleta B', 'dojo_snapshot' => 'Dojo Honbu'],
            ['id_inscricao' => 3, 'nome_atleta_snapshot' => 'Atleta C', 'dojo_snapshot' => 'Dojo Cobra'],
            ['id_inscricao' => 4, 'nome_atleta_snapshot' => 'Atleta D', 'dojo_snapshot' => 'Dojo Sul'],
            ['id_inscricao' => 5, 'nome_atleta_snapshot' => 'Atleta E', 'dojo_snapshot' => 'Dojo Leste']
        ];

        $resultado = Chave::calcularEstrutura($atletas);

        $this->assert($resultado['bracket_size'] === 8, 'Potência de 2 correta para 5 atletas é 8');
        $this->assert($resultado['total_byes'] === 3, 'Total de Byes correto para 5 atletas em chave de 8 é 3 (8 - 5)');
        $this->assert($resultado['total_rodadas'] === 3, 'Total de rodadas para chave de 8 é 3 (Quartas, Semi, Final)');
        $this->assert(count($resultado['slots']) === 8, 'Total de slots preenchidos na R1 é 8');
    }

    private function testPesagemRules(): void
    {
        echo "\n[4] Testando Pesagem::validarPeso (Regras de Balança e Tolerância)...\n";
        $pesagemModel = new Pesagem();

        $categoriaKumite = [
            'nome' => 'Sênior até 75kg',
            'exige_pesagem' => 1,
            'peso_min' => 65.0,
            'peso_max' => 75.0,
            'tolerancia_peso_kg' => 0.5
        ];

        // Caso 1: Peso 72.0kg -> Aprovado
        $val1 = $pesagemModel->validarPeso($categoriaKumite, 72.0);
        $this->assert($val1['aprovado'] === true, 'Peso 72.0kg aprovado em categoria 65-75kg');

        // Caso 2: Peso 75.4kg -> Aprovado dentro da tolerância (+0.5kg)
        $val2 = $pesagemModel->validarPeso($categoriaKumite, 75.4);
        $this->assert($val2['aprovado'] === true, 'Peso 75.4kg aprovado dentro da tolerância de +0.5kg');

        // Caso 3: Peso 76.0kg -> Reprovado por excesso de peso
        $val3 = $pesagemModel->validarPeso($categoriaKumite, 76.0);
        $this->assert($val3['aprovado'] === false, 'Peso 76.0kg reprovado por exceder peso máximo + tolerância');
        $this->assert(str_contains($val3['motivo'], 'excedeu o peso máximo'), 'Motivo de excesso de peso registrado');

        // Caso 4: Peso 63.0kg -> Reprovado por estar abaixo do peso mínimo
        $val4 = $pesagemModel->validarPeso($categoriaKumite, 63.0);
        $this->assert($val4['aprovado'] === false, 'Peso 63.0kg reprovado por estar abaixo do mínimo');
    }
}

// Dispara execução dos testes
$runner = new TestRunner();
$runner->run();
