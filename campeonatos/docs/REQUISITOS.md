# ESPECIFICAÇÃO DE REQUISITOS DO SISTEMA
## Plataforma de Gestão de Campeonatos e Súmulas Kenshydokan Jutsu
**Subdomínio**: `campeonatos.kenshydokan.org.br`  
**Versão**: 1.0.0  
**Ambiente**: PHP 8.4 puro | MariaDB (`campeonatos`) | Apache WAMP64 / Hostinger

---

## 1. Visão Geral do Produto
O **Sistema Oficial de Gestão de Campeonatos e Súmulas Kenshydokan Jutsu** é uma solução corporativa esportiva desenvolvida para gerenciar integralmente o ciclo de vida de competições de artes marciais da organização Kenshydokan Jutsu. O sistema administra multicampeonatos independentes, inscrições de atletas baseadas no cadastro mestre de filiados, pesagem oficial, geração de chaves eliminatórias com byes e repescagem, súmulas eletrônicas em tempo real para três modalidades (Kumite, Kata e Tameshiwari), gestão simultânea de tatames, homologação de resultados, classificação geral e emissão automatizada de certificados e relatórios em PDF.

---

## 2. Requisitos Não Funcionais (RNF)

- **RNF01 — Tecnologia Núcleo**: A aplicação deve ser desenvolvida estritamente em **PHP 8.4 puro**, sem o uso de frameworks (Laravel, Symfony, CodeIgniter, Slim, etc.) e sem dependência de Composer para o autoloader.
- **RNF02 — Banco de Dados**: Utilizar **MariaDB** com charset `utf8mb4_unicode_ci` e motor `InnoDB`. Todas as operações de leitura e escrita devem ser realizadas por meio da extensão nativa **`mysqli`** com **Prepared Statements** obrigatórios em 100% das consultas com parâmetros.
- **RNF03 — Isolamento Arquitetural**: O sistema residirá em um banco de dados próprio chamado `campeonatos`. O banco do site principal (`u515961161_kenshydokan`) será acessado em modo **estritamente somente-leitura** para consulta aos filiados. Nenhum arquivo do domínio raiz será alterado.
- **RNF04 — Arquitetura de Software**: Adoção do padrão arquitetural **MVC (Model-View-Controller)** com separação estrita de camadas:
  - `Core/`: Roteamento, conexão segura mysqli, sessão, CSRF e controladores base.
  - `Models/`: Mapeamento de tabelas e operações SQL.
  - `Views/`: Apresentação em HTML5 responsivo.
  - `Controllers/`: Tratamento de requisições e fluxo de controle.
  - `Services/`: Regras de negócio complexas (motores de pontuação, sorteio de chaves, geração de PDFs).
  - `Validators/`: Validação de entradas e regras de elegibilidade.
- **RNF05 — Bibliotecas Locais**: Utilizar exclusivamente as bibliotecas já disponíveis no workspace (`libs/`):
  - Bootstrap 5 (design responsivo e acessível);
  - Select2.js (seleção pesquisável de atletas e categorias);
  - DataTables.js (tabelas interativas com paginação, filtros e ordenação);
  - TinyMCE (edição de regulamentos e comunicados oficiais);
  - Chart.js (gráficos e dashboards estatísticos);
  - Font Awesome 6 (iconografia);
  - AlertifyJS (alertas assíncronos e modais de confirmação);
  - FPDF (geração de documentos e certificados em PDF).
- **RNF06 — Performance e Tempo Real**: A interface de arbitragem em tatame (súmula digital) deve registrar pontos, faltas e cronômetro via chamadas assíncronas (AJAX com fetch/XMLHttpRequest) sem recarregar a página, com reconciliação de estado no servidor e tempo de resposta inferior a 200ms.
- **RNF07 — Segurança da Informação**:
  - Senhas criptografadas com `password_hash()` (algoritmo BCRYPT) e validadas com `password_verify()`.
  - Regeneração do ID de sessão no login (`session_regenerate_id(true)`).
  - Proteção contra Cross-Site Request Forgery (CSRF) com tokens descartáveis por sessão.
  - Escape obrigatório de saídas com `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`.
  - Tratamento de exceções sem exibição de stack trace, paths ou erros SQL ao usuário final.
- **RNF08 — Auditoria e Rastreabilidade**: Registro inalterável de auditoria para todas as operações críticas (alteração de pontuação, retificação de súmula, mudança de vencedor, desclassificação, homologação).

---

## 3. Requisitos Funcionais (RF)

### Módulo 1: Autenticação e Perfis de Acesso
- **RF01.1**: O sistema deve permitir autenticação de usuários cadastrados no banco `campeonatos`.
- **RF01.2**: Suportar os perfis de acesso:
  - **Administrador**: Gestão completa de campeonatos, categorias, usuários, configurações visuais, homologação final e auditoria.
  - **Operador de Tatame / Mesa**: Operação do cronômetro, preenchimento de súmula digital de lutas/apresentações alocadas ao seu tatame, pesagem e chamada de atletas.
  - **Árbitro Central / Lateral**: Consulta e assinatura de súmulas.
- **RF01.3**: Bloquear tentativas excessivas de login (bloqueio temporário após 5 tentativas inválidas consecutivas).

### Módulo 2: Gestão de Multicampeonatos
- **RF02.1**: Permitir a criação, edição, duplicação, arquivamento e consulta de múltiplos campeonatos independentes.
- **RF02.2**: Cada campeonato conterá: título, edição, local, data de início, data de término, data de referência para cálculo de idade, organizador, modalidades ativas e status do ciclo de vida.
- **RF02.3**: Controlar o ciclo de vida por estados explícitos:
  - `planejamento` -> `inscricoes_abertas` -> `inscricoes_encerradas` -> `conferencia_pesagem` -> `chaveamento` -> `em_andamento` -> `encerrado` -> `arquivado`.
- **RF02.4**: Impedir alterações estruturais destrutivas em campeonatos com status `encerrado` ou `arquivado` sem procedimento específico de reabertura auditada.

### Módulo 3: Configuração de Categorias
- **RF03.1**: Permitir criar categorias específicas por campeonato para as modalidades Kumite, Kata e Tameshiwari.
- **RF03.2**: Cada categoria poderá ser delimitada por:
  - Modalidade esportiva;
  - Sexo (Masculino, Feminino ou Misto/Aberto);
  - Faixa etária (idade mínima e máxima na data de referência do campeonato);
  - Limites de peso (peso mínimo e peso máximo em kg);
  - Agrupamento de graduações ou faixas específicas (ex: Iniciante, Intermediário, Avançado, Mestre);
  - Nível competitivo (ex: Escolar, Universitário, Principal, Master).
- **RF03.3**: Validar para que não haja categorias duplicadas com os mesmos critérios exatos dentro do mesmo campeonato.

### Módulo 4: Inscrições e Integração com Filiados
- **RF04.1**: Permitir a busca e seleção de atletas diretamente da tabela `filiados` do banco principal.
- **RF04.2**: Permitir inscrições múltiplas de um mesmo filiado em diferentes modalidades/categorias, desde que atenda aos critérios regulamentares de cada uma.
- **RF04.3**: Bloquear inscrição duplicada do mesmo filiado na mesma categoria.
- **RF04.4**: Gravar um **Snapshot Imutável** no momento da confirmação da inscrição contendo: nome, dojo/academia, graduação, data de nascimento, idade no campeonato e sexo.
- **RF04.5**: Sinalizar pendências cadastrais (como data de nascimento não preenchida `'0000-00-00'` ou filiação não confirmada).

### Módulo 5: Pesagem Oficial
- **RF05.1**: Registrar pesagem dos atletas inscritos em categorias que exigem conferência de peso.
- **RF05.2**: O registro de pesagem deve conter: peso aferido, unidade (kg), data e hora, operador responsável, status (`aprovado` ou `reprovado`), diferença para o limite da categoria e observações.
- **RF05.3**: Manter o histórico de todas as tentativas de pesagem sem apagar registros anteriores.
- **RF05.4**: Bloquear a liberação do atleta para o chaveamento enquanto o status da pesagem estiver como reprovado ou pendente.
- **RF05.5**: Permitir remanejamento do atleta para outra categoria compatível com o peso aferido, mediante justificativa registrada e auditada.

### Módulo 6: Sorteio e Chaveamento
- **RF06.1**: Gerar chaves eliminatórias automáticas com sorteio auditável e reproduzível.
- **RF06.2**: Suportar formatos de **Eliminatória Simples** e **Eliminatória com Repescagem**.
- **RF06.3**: Tratar adequadamente quantidades de atletas que não são potências de 2 (3, 5, 6, 7, 9, etc.), aplicando a distribuição oficial de *byes* (isenções de primeira rodada) de maneira equilibrada nas chaves superior e inferior.
- **RF06.4**: Evitar, sempre que possível, que atletas da mesma academia/dojo se enfrentem na primeira rodada.
- **RF06.5**: Tratar ausências (*W.O.*), desistências e desclassificações com avanço automático do adversário.
- **RF06.6**: Renderizar visualmente a árvore de chaveamento interativa na tela e permitir a impressão em formato PDF.

### Módulo 7: Gestão de Tatames e Operação Simultânea
- **RF07.1**: Cadastrar tatames / áreas de competição numeradas.
- **RF07.2**: Alocar lutas das categorias aos tatames e ordenar a fila de espera.
- **RF07.3**: Exibir o painel do tatame com: luta em andamento, próxima luta na área de aquecimento e lutas aguardando chamada.
- **RF07.4**: Implementar controle de concorrência com bloqueio transacional para impedir que dois operadores operem a mesma luta simultaneamente.

### Módulo 8: Súmula Digital de Kumite
- **RF08.1**: Interface de tela cheia, alto contraste e operação por toque/teclado para árbitros de mesa.
- **RF08.2**: Identificação clara dos atletas: Lado Vermelho (*Aka*) e Lado Branco (*Shiro*).
- **RF08.3**: Cronômetro principal (3 minutos corridos) com pausa, reinício e controle de acréscimo.
- **RF08.4**: Cronômetro auxiliar de solo (*Ne Waza*) de até 60 segundos a partir da estabilização da queda. Alerta visual de 10 segundos de inatividade.
- **RF08.5**: Cronômetro auxiliar de contagem protetora (*Knockdown*) de até 10 segundos.
- **RF08.6**: Registro de pontuações:
  - Ippon (1.0 ponto);
  - Waza-ari (0.5 ponto);
  - Sem Ponto (0.0 ponto).
- **RF08.7**: Encerramento antecipado automático por **6 Waza-ari (3.0 pontos somados)** (*Waza-ari Awasete Ippon*).
- **RF08.8**: Prorrogação automática de 1 minuto em **Golden Score** em caso de empate ao término do tempo regulamentar.
- **RF08.9**: Registro e conversão automática de penalidades:
  - Shido (advertência simples);
  - Chui (advertência oficial); conversão automática de **2 Chui em 0.5 ponto (Waza-ari)** para o adversário;
  - Saídas de área consecutivas com concessão de Waza-ari ao adversário;
  - Hansoku-make (desclassificação direta).
- **RF08.10**: Decisão por árbitros (*Hantei*) com votação da banca (1 central + 4 laterais) e consulta obrigatória ao histórico de Tameshiwari dos atletas em caso de empate persistente.
- **RF08.11**: Registro de ocorrência detalhado segundo a segundo (log de eventos da luta).

### Módulo 9: Súmula Digital de Kata
- **RF09.1**: Cadastro de banca avaliadora com 3 a 5 árbitros.
- **RF09.2**: Lançamento de notas individuais de 0.0 a 10.0 com precisão de uma casa decimal (décimos).
- **RF09.3**: Apuração da média aritmética simples oficial.
- **RF09.4**: Fluxo de desempate com registro de nova rodada para execução de novo Kata de livre escolha, preservando o histórico da rodada anterior.
- **RF09.5**: Registro do nome do Kata executado pelo atleta.

### Módulo 10: Súmula Digital de Tameshiwari
- **RF10.1**: Registro das 4 oficinas técnicas oficiais: *Seiken*, *Shuto*, *Hiji (Empi)* e *Chusoku/Sokuto/Kakato*.
- **RF10.2**: Registro de material (madeira, gelo, telhas), quantidade declarada e quantidade efetivamente quebrada.
- **RF10.3**: Validação de 1 tentativa de até 60 segundos por oficina; quebramento parcial ou falha resulta em nota 0 na estação.
- **RF10.4**: Cálculo do total de unidades válidas quebradas para a classificação final da modalidade e disponibilização dos dados para o critério de desempate no Kumite.

### Módulo 11: Homologação e Publicação de Resultados
- **RF11.1**: Fluxo em duas etapas: resultado provisório -> homologação oficial com conferência da equipe de arbitragem.
- **RF11.2**: Bloqueio de alteração em súmulas já homologadas, exigindo justificativa formal e perfil de administrador para retificação.
- **RF11.3**: Publicação da classificação oficial por categoria e quadro geral de medalhas por academia.

### Módulo 12: Certificados e Relatórios em PDF
- **RF12.1**: Emissão automatizada via FPDF de certificados oficiais (Campeão, Vice-campeão, 3º Lugar, Participação, Árbitro, Organizador).
- **RF12.2**: Código de autenticidade único e verificável para cada certificado emitido.
- **RF12.3**: Geração em PDF de todos os 20 relatórios oficiais: Súmula individual de Kumite, Kata e Tameshiwari, chaveamentos completos, listas de pesagem, relação de inscritos, programação por tatame, classificação final e auditoria.

### Módulo 13: Dashboard e Indicadores
- **RF13.1**: Painel estatístico interativo com Chart.js exibindo: inscritos por categoria, distribuição por faixa etária/sexo/graduação, lutas realizadas vs pendentes, formas de vitória no Kumite e medalhas por dojo.
