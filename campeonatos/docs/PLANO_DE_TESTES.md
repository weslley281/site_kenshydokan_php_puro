# PLANO DE TESTES E CONTROLE DE QUALIDADE
## Sistema de Campeonatos e Súmulas Kenshydokan Jutsu
**Escopo**: Testes Unitários de Regras de Negócio e Testes de Integração de Fluxos

---

## 1. Estratégia de Testes

Os testes garantem a conformidade estrita com o Livro de Regras Oficiais, a blindagem da integridade referencial e a estabilidade da aplicação sob alta carga e concorrência no dia da competição.

Os testes são executados localmente via CLI com PHP 8.4 puro em script de teste automatizado (`tests/run_tests.php`).

---

## 2. Matriz de Casos de Teste Obrigatórios

| ID | Módulo / Cenário | Descrição do Teste | Critério de Aceitação / Resultado Esperado |
| :--- | :--- | :--- | :--- |
| **TC01** | Integração Filiados | Leitura de filiados no banco raiz `u515961161_kenshydokan` | Retorna filiados ativos sem alterar nenhum dado no banco corporativo. |
| **TC02** | Snapshot Inscrição | Criação de inscrição e persistência de snapshot | Nome, dojo, faixa e data de nascimento são congelados em `campeonato_inscricoes`. |
| **TC03** | Cálculo de Idade | Atleta com aniversário após data de referência | Idade calculada com base em `data_referencia_idade` do campeonato, não em `CURDATE()`. |
| **TC04** | Bloqueio Cadastral | Filiado com data de nascimento inválida (`0000-00-00`) | Sistema bloqueia inscrição e exige regularização do cadastro antes da homologação. |
| **TC05** | Inscrição Duplicada | Tentar inscrever o mesmo filiado 2 vezes na mesma categoria | Sistema dispara erro de unicidade e impede o registro duplicado. |
| **TC06** | Inscrições Múltiplas | Inscrever o mesmo filiado em Kumite e Kata no mesmo evento | Ambas as inscrições são aceitas e vinculadas às suas respectivas categorias. |
| **TC07** | Pesagem Aprovada | Peso aferido dentro dos limites da categoria | Status da pesagem marcado como `aprovado`; atleta liberado para a chave. |
| **TC08** | Pesagem Reprovada | Peso acima do limite máximo da categoria | Status marcado como `reprovado`; atleta bloqueado no chaveamento. |
| **TC09** | Chaveamento Potência de 2 | Geração de chave com 8 atletas | Gera chave clássica de 3 rodadas (Quartas, Semifinal e Final) sem byes. |
| **TC10** | Chaveamento com Byes | Geração de chave com 5 atletas | Calcula 3 byes ($2^3 - 5 = 3$), distribuídos equilibradamente entre topo e fundo da chave. |
| **TC11** | Proteção de Dojos | Sorteio com atletas da mesma academia | Algoritmo aloca atletas do mesmo dojo em metades opostas da chave na rodada 1. |
| **TC12** | Pontuação Kumite | Adição de 1 Waza-ari (0.5 ponto) | Placar incrementa 0.5 ponto e grava evento no histórico com segundo da luta. |
| **TC13** | Regra 6 Waza-ari | Acúmulo de 6 Waza-ari para um atleta | Sistema dispara encerramento antecipado automático (*Waza-ari Awasete Ippon*). |
| **TC14** | Conversão de Chui | Atleta recebe 2º Chui por passividade | Placar concede automaticamente 0.5 ponto (Waza-ari) para o adversário. |
| **TC15** | Tempo de Solo (Ne Waza)| Cronômetro de solo atinge 60 segundos | Sistema emite sinal sonoro/visual de interrupção (*Yame*) e reinício em pé. |
| **TC16** | Golden Score | Luta termina empatada aos 3:00 regulamentares | Prorrogação de 1:00 é iniciada; primeiro atleta a pontuar vence imediatamente. |
| **TC17** | Decisão Hantei | Empate persistente após Golden Score | Sistema exibe painel de votação dos 5 árbitros e exibe dados de Tameshiwari. |
| **TC18** | Média de Kata | Banca com 5 árbitros atribuindo notas | Média aritmética exata calculada com precisão de 1 casa decimal. |
| **TC19** | Desempate de Kata | Dois atletas empatados na 1ª colocação | Sistema gera rodada de desempate para novo Kata, mantendo notas anteriores. |
| **TC20** | Tameshiwari Falha | Atleta quebra parcialmente as tábuas de uma oficina | Sistema registra nota 0 na oficina e soma apenas as estações válidas. |
| **TC21** | Homologação de Luta | Encerramento de luta no tatame | Vencedor avança automaticamente para a próxima rodada na árvore de chaves. |
| **TC22** | Retificação Auditada | Alteração de resultado homologado pelo Admin | Exige justificativa formal; registra dados anteriores e novos em `campeonato_auditoria`. |
| **TC23** | Certificado Hash | Emissão de certificado oficial em PDF | Gera arquivo FPDF com hash único que pode ser consultado na URL pública de validação. |
| **TC24** | Segurança CSRF | Envio de formulário POST sem token CSRF | Requisição rejeitada com HTTP 403 Forbidden. |
| **TC25** | Concorrência Tatame | Dois navegadores tentando operar o mesmo tatame | Sistema detecta colisão e alerta o segundo operador sobre sessão concorrente. |
