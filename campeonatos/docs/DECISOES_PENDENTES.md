# REGISTRO DE DECISÕES E PENDÊNCIAS REGULAMENTARES
## Sistema de Campeonatos e Súmulas Kenshydokan Jutsu

Este documento registra explicitamente a origem de cada regra, as decisões de engenharia adotadas e os pontos que permanecem abertos para homologação pela diretoria da Kenshydokan Jutsu.

---

## 1. Classificação das Definições do Sistema

### A. Regras Extraídas do Livro Oficial de Regras
- Duração da luta de Kumite: 3 minutos corridos;
- Encerramento antecipado por pontuação (*Waza-ari Awasete Ippon*): acúmulo de **6 Waza-ari (3.0 pontos)**;
- Prorrogação: 1 minuto em **Golden Score** (primeiro ponto encerra);
- Decisão arbitral por **Hantei** com consulta obrigatória ao Tameshiwari em empate persistente;
- Teto de solo (*Ne Waza*): 60 segundos corridos a partir da estabilização da queda;
- Advertência por passividade no clinch (> 5s) e no solo (> 10s): aplicação de **Chui**;
- Conversão de penalidades: **2 Chuis = 0.5 ponto (Waza-ari)** concedido ao adversário;
- Protocolo de atordoamento (knockdown): contagem oficial de até 10 segundos pelo árbitro central;
- Desclassificação imediata (*Hansoku-make*) por simulação comprovada de lesão para descanso;
- Notas de Kata: banca de 3 a 5 árbitros, escala de 0.0 a 10.0, apuração por média aritmética simples e desempate com novo kata;
- Tameshiwari: 4 oficinas (*Seiken, Shuto, Hiji, Chusoku/Sokuto/Kakato*), 1 tentativa de até 60s por oficina, falha = nota 0;
- Proteções por idade: < 14 anos com bucal, coquilha, peitoral e caneleiras; >= 14 anos sem peitoral e sem caneleiras.

### B. Requisitos Definidos pelo Responsável pelo Sistema
- Criação de subdomínio dedicado: `campeonatos.kenshydokan.org.br`;
- Criação de banco de dados independente no MariaDB: `campeonatos`;
- Acesso estritamente **somente-leitura** à tabela `filiados` do banco do site raiz (`u515961161_kenshydokan`);
- Nenhum arquivo do domínio raiz será alterado.

### C. Decisões Técnicas de Engenharia Adotadas
- Padrão **Dual-Database** em PHP puro com conexão nativa `mysqli` gerenciando Prepared Statements;
- **Snapshot Cadastral Imutável** na tabela `campeonato_inscricoes`: os dados do atleta na data do evento são congelados para blindar súmulas históricas de alterações futuras no cadastro corporativo;
- Utilização estrita das bibliotecas locais já existentes na pasta `libs/` (Bootstrap, Select2, DataTables, TinyMCE, Chart.js, Font Awesome, AlertifyJS, FPDF).

---

## 2. Decisões Pendentes e Propostas Fundamentadas

### Pendência 01 — Tabela Padronizada de Categorias de Peso
- **Situação**: O livro de regras não fixa uma tabela com os limites exatos de peso em quilogramas (ex.: -60kg, -70kg, -80kg, Pesado, Absoluto).
- **Impacto no Sistema**: O módulo de pesagem precisa de limites numéricos (`peso_min` e `peso_max`) para aprovar ou reprovar o atleta.
- **Proposta Técnica Adotada**: Criar o configurador de categorias totalmente dinâmico por campeonato (`campeonato_categorias`). O sistema disponibilizará um botão de carga de template padrão (baseado nas divisões tradicionais de Karate de Contato e Budo) que o administrador poderá editar livremente para cada torneio.
- **Status**: *Proposta implantada no sistema, aguardando validação dos valores pelo diretor técnico.*

### Pendência 02 — Formato do Algoritmo de Repescagem
- **Situação**: O livro oficial estipula que o campeonato pode adotar repescagem, mas não define se adota o modelo olímpico ou disputa simples de 3º lugar.
- **Impacto no Sistema**: O algoritmo de geração de chaves precisa saber se cria chaves de repescagem cruzadas para quem perdeu dos finalistas ou se agenda apenas uma luta entre os dois perdedores das semifinais.
- **Proposta Técnica Adotada**: O sistema suportará ambas as opções via seletor na configuração do campeonato:
  - *Opção A (Padrão sugerido - Repescagem Olímpica Tradicional)*: Atletas derrotados pelos dois finalistas disputam chaves de repescagem, gerando dois terceiros lugares (duas medalhas de bronze).
  - *Opção B (Disputa Direta de 3º Lugar)*: Os perdedores das duas semifinais se enfrentam para definir um único 3º lugar.
- **Status**: *Aguardando homologação formal de qual opção será o padrão oficial da Kenshydokan.*

### Pendência 03 — Mínimo Regulamentar de Tábuas no Tameshiwari
- **Situação**: O regulamento menciona *"respeitando o mínimo regulamentar"*, sem explicitar a quantidade numérica de tábuas/telhas mínimas.
- **Impacto no Sistema**: A interface de declaração de quantidade deve validar o valor mínimo inserido.
- **Proposta Técnica Adotada**: Criar campo de configuração por campeonato:
  - Padrão sugerido: 2 tábuas para Adulto Masculino; 1 tábua para Feminino, Juvenil e Master.
- **Status**: *Proposta implantada de forma configurável.*

### Pendência 04 — Captura do Campo `sexo`
- **Situação**: A tabela legada `filiados` não possui coluna `sexo`. As categorias oficiais de Kumite e equipamentos de proteção exigem a informação de sexo.
- **Impacto no Sistema**: A validação de elegibilidade e proteções depende do sexo.
- **Decisão Adotada**: Para cumprir rigorosamente a diretriz de **não alterar o banco nem os arquivos do site raiz**, o dado de sexo será informado e validado exclusivamente na inscrição do campeonato e persistido no snapshot do banco `campeonatos`.
- **Status**: *Resolvido de forma segura sem tocar no domínio raiz.*
