# MANUAL DO ADMINISTRADOR E OPERADOR
## Sistema de Campeonatos e Súmulas Kenshydokan Jutsu
**Guia Operacional Passo a Passo**

---

## 1. Introdução
Este manual descreve o procedimento operacional padrão para a gestão completa de uma competição esportiva na plataforma oficial da Kenshydokan Jutsu, desde o planejamento inicial até a homologação final dos resultados e entrega de certificados.

---

## 2. Passo a Passo da Gestão do Campeonato

### Passo 1 — Criando um Novo Campeonato
1. No menu lateral, acesse **Campeonatos** e clique em **Novo Campeonato**.
2. Preencha os campos obrigatórios:
   - **Título**: Ex.: *"Campeonato Estadual Kenshydokan Jutsu 2026"*;
   - **Edição**: Ex.: *"VI Edição"*;
   - **Local e Cidade/Estado**: Endereço do ginásio;
   - **Data de Início e Término**;
   - **Data de Referência de Idade**: Data base utilizada para calcular a idade exata de cada competidor (geralmente a data de início do evento);
   - **Modalidades Habilitadas**: Selecione *Kumite*, *Kata* e/ou *Tameshiwari*.
3. O campeonato será criado no estado `planejamento`.

### Passo 2 — Configurando as Categorias
1. Dentro do campeonato criado, acesse a aba **Categorias**.
2. Clique em **Adicionar Categoria** ou utilize o botão **Carregar Modelo Padrão** para importar automaticamente as divisões oficiais sugeridas.
3. Para cada categoria, configure:
   - Modalidade (*Kumite*, *Kata* ou *Tameshiwari*);
   - Sexo (*Masculino* ou *Feminino*);
   - Faixa etária (Ex.: 14 a 17 anos);
   - Limites de peso (Ex.: mínimo 65.0 kg e máximo 75.0 kg para categoria Médio);
   - Faixa de graduação autorizada (Ex.: de Faixa Roxa a Faixa Preta 1º Dan).

### Passo 3 — Abertura e Gestão de Inscrições
1. Mude o status do campeonato para `inscricoes_abertas`.
2. Para inscrever um atleta:
   - Acesse **Inscrições** > **Nova Inscrição**;
   - Digite o nome ou código do atleta no campo pesquisável (Select2); o sistema buscará os registros da tabela `filiados` do site raiz;
   - Selecione a categoria pretendida;
   - O sistema valida automaticamente se a idade, sexo e graduação são compatíveis;
   - Ao confirmar, o sistema grava o **Snapshot Imutável** do atleta no banco `campeonatos`.

### Passo 4 — Realizando a Pesagem Oficial
1. No dia do evento ou na véspera, altere o status para `conferencia_pesagem`.
2. Na aba **Pesagem**, posicione a balança junto ao operador de pesagem:
   - Busque a inscrição do atleta;
   - Digite o peso aferido em kg na balança de precisão;
   - O sistema compara o valor com os limites da categoria:
     - **Dentro do limite**: Marca como `aprovado` e libera para a chave;
     - **Fora do limite**: Marca como `reprovado`, exibe a diferença em gramas/kg e bloqueia o chaveamento.
3. Se um atleta for reprovado, o Administrador pode clicar em **Remanejar Categoria**, selecionando a nova categoria correspondente ao peso aferido e registrando uma justificativa formal para a auditoria.

### Passo 5 — Sorteio e Geração de Chaves
1. Com as pesagens concluídas, altere o status do campeonato para `chaveamento`.
2. Acesse **Chaveamento** e selecione a categoria desejada.
3. Clique em **Gerar Sorteio Oficial**:
   - O sistema processa os competidores aptos, calcula os *byes* matemáticos necessários e distribui atletas da mesma academia em metades opostas;
   - A árvore eliminatória é renderizada na tela com layout interativo;
   - Clique em **Imprimir Chave (PDF)** para fixar a chave no mural do ginásio.

### Passo 6 — Alocação de Tatames e Fila de Lutas
1. Acesse **Gestão de Tatames**;
2. Cadastre os tatames do evento (Ex.: *Tatame 1 - Principal*, *Tatame 2*);
3. Distribua as categorias entre os tatames disponíveis;
4. O sistema gera a fila de espera ordenada, permitindo chamar a próxima luta enquanto o combate atual está em andamento.

### Passo 7 — Operação da Súmula Digital de Kumite
O operador da mesa abre a luta alocada no tatame:
1. **Cronômetro Geral**: Clique em **Iniciar (Espaço)** para rodar os 3 minutos regulamentares.
2. **Pontuação**:
   - Botões vermelhos (**Aka**) e brancos (**Shiro**);
   - Clique em **Waza-ari (+0.5)** ou **Ippon (+1.0)**;
   - O placar atualiza em tempo real via AJAX;
   - Se um atleta atingir **6 Waza-ari (3.0 pontos)**, o sistema toca o alarme de encerramento antecipado e declara a vitória automaticamente.
3. **Penalidades**:
   - Clique em **Chui** para advertências oficiais. Ao registrar o 2º Chui para o mesmo lutador, o sistema concede automaticamente 0.5 ponto ao oponente.
4. **Solo (Ne Waza)**:
   - Ao estabilizar a queda, clique em **Cronômetro de Solo**; o contador regressivo de até 60 segundos é ativado, com alerta de 10s de passividade.
5. **Knockdown**:
   - Acione a contagem protetora de 10s caso o atleta acuse o golpe.
6. **Empate ao final dos 3 min**:
   - O sistema abre a prorrogação de 1 minuto em **Golden Score**;
   - Se persistir o empate, abre-se a tela de **Hantei**, exibindo o histórico de Tameshiwari dos dois atletas para subsidiar a decisão dos 5 árbitros.

### Passo 8 — Operação de Kata e Tameshiwari
- **Kata**: A mesa seleciona a categoria, chama o atleta, insere o nome do Kata e digita as notas atribuídas pelos 3 a 5 árbitros (de 0.0 a 10.0). O sistema calcula a média aritmética. Em caso de empate, abre a rodada de desempate para um novo kata.
- **Tameshiwari**: O operador registra a quantidade declarada e a quantidade efetivamente quebrada em cada uma das 4 estações (*Seiken, Shuto, Hiji, Chusoku*). Se falhar, registra 0 naquela estação. O somatório geral classifica o campeão.

### Passo 9 — Homologação e Pódio Oficial
1. Ao término das finais, o Administrador confere os resultados na aba **Classificações** e clica em **Homologar Categoria**.
2. A homologação fecha o resultado oficial (1º Ouro, 2º Prata, 3º Bronze).
3. O quadro geral de medalhas por academia é atualizado instantaneamente.

### Passo 10 — Emissão de Certificados e Relatórios
1. Acesse **Certificados**:
   - Clique em **Emitir Todos os Certificados em Lote (PDF)**;
   - O sistema gera os certificados personalizados com nome do atleta, categoria, colocação e um **código hash de autenticidade** exclusivo;
2. Qualquer pessoa com o documento em mãos pode acessar a página pública de verificação do subdomínio e validar a autenticidade do certificado informando o código impresso.
