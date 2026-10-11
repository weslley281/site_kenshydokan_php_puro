# FLUXOS OPERACIONAIS E DIAGRAMAS DE SEQUÊNCIA
## Sistema de Campeonatos e Súmulas Kenshydokan Jutsu
**Módulos**: Ciclo de Vida, Inscrição, Pesagem, Chaveamento, Kumite, Kata, Tameshiwari e Homologação

---

## 1. Fluxo do Ciclo de Vida do Campeonato

```mermaid
stateDiagram-v2
    [*] --> Planejamento: Criar campeonato e definir regras
    Planejamento --> InscricoesAbertas: Publicar e abrir prazo
    InscricoesAbertas --> InscricoesEncerradas: Prazo final atingido
    InscricoesEncerradas --> ConferenciaPesagem: Convocação para balança
    ConferenciaPesagem --> Chaveamento: Homologar atletas aptos
    Chaveamento --> EmAndamento: Iniciar combates nos tatames
    EmAndamento --> HomologacaoResultados: Concluir finais de categorias
    HomologacaoResultados --> Encerrado: Publicar pódios e certificados
    Encerrado --> Arquivado: Armazenamento histórico
```

---

## 2. Fluxo de Inscrição e Criação de Snapshot

```mermaid
sequenceDiagram
    autonumber
    actor Admin as Organizador / Atleta
    participant Web as Painel de Inscrições
    participant Val as InscricaoValidator
    participant DB_Root as Banco Raiz (filiados)
    participant DB_Camp as Banco campeonatos
    
    Admin->>Web: Seleciona Atleta e Categoria pretendida
    Web->>DB_Root: SELECT id_filiado, nome, dojo, dataNascimento, confirmacao
    DB_Root-->>Web: Dados cadastrais retornados
    Web->>Val: Valida regras da Categoria (Idade, Sexo, Graduação)
    alt Regras Incompatíveis ou Pendência
        Val-->>Web: Erro (Ex: Atleta abaixo da idade mínima ou dados incompletos)
        Web-->>Admin: Alerta visual com motivo do bloqueio
    else Elegibilidade Confirmada
        Val-->>Web: Aprovado
        Web->>DB_Camp: INSERT INTO campeonato_inscricoes (Snapshot dos dados)
        DB_Camp-->>Web: Inscrição confirmada (Status: Pendente de Pesagem)
        Web-->>Admin: Comprovante de inscrição gerado
    end
```

---

## 3. Fluxo de Pesagem Oficial e Balança

```mermaid
flowchart TD
    Inicio([Atleta apresenta-se à Balança]) --> Consulta[Operador busca inscrição por nome ou código]
    Consulta --> Afericao[Operador digita peso aferido em kg]
    Afericao --> Checagem{Peso está dentro do limite da Categoria?}
    
    Checagem -- Sim --> Aprovar[Registra Pesagem APROVADA]
    Aprovar --> Libera[Libera atleta para o Chaveamento] --> Fim([Atleta Apto])
    
    Checagem -- Não --> Reprovar[Registra Pesagem REPROVADA e diferença de peso]
    Reprovar --> Bloquear[Bloqueia atleta na categoria atual]
    Bloquear --> Decisao{Autorizada mudança de categoria?}
    
    Decisao -- Sim --> Remanejar[Admin autoriza e remaneja para categoria compatível]
    Remanejar --> Audit[Grava justificativa na Auditoria] --> Fim
    
    Decisao -- Não --> Desclassificar[Atleta inabilitado para o evento] --> Fim
```

---

## 4. Fluxo de Sorteio e Geração de Chaves (Eliminatórias)

```mermaid
flowchart TD
    A([Início da Geração da Chave]) --> B[Filtrar atletas da categoria com status 'aprovada']
    B --> C{Total de Atletas N}
    
    C --> D[Calcular próxima potência de 2: 2^k]
    D --> E[Calcular Byes = 2^k - N]
    E --> F[Embaralhamento seguro com random_int auditável]
    F --> G[Agrupar por academia/dojo para evitar confrontos diretos na R1]
    G --> H[Distribuir Byes balanceados entre Chave Superior e Inferior]
    H --> I[Gravar campeonato_chaves e campeonato_lutas]
    I --> J[Gerar árvore visual e PDF oficial da chave] --> K([Chave Pronta para Tatame])
```

---

## 5. Fluxo de Arbitragem de Kumite em Tempo Real (Súmula Digital)

```mermaid
sequenceDiagram
    autonumber
    actor Mesa as Árbitro de Mesa / Operador
    participant Sumula as Interface Súmula Kumite (AJAX)
    participant Engine as KumiteEngineService
    participant DB as Banco campeonatos
    
    Mesa->>Sumula: Iniciar Combate (Cronômetro 3:00)
    Sumula->>DB: Status da Luta = 'em_andamento'
    
    loop Durante os 3 Minutos
        alt Golpe Contundente / Queda
            Mesa->>Sumula: Clica "Waza-ari (+0.5)" para Lado Aka
            Sumula->>Engine: Processa Ponto e Acúmulo
            Engine-->>Sumula: Atualiza placar
            opt Acúmulo atingiu 6 Waza-ari (3.0 pts)
                Engine-->>Sumula: DISPARA ENCERRAMENTO ANTECIPADO (Waza-ari Awasete Ippon)
                Sumula-->>Mesa: Luta encerrada! Vencedor: Aka
            end
        else Falta / Passividade
            Mesa->>Sumula: Aplica "Chui" por passividade
            Sumula->>Engine: Registra falta
            opt Acúmulo atingiu 2 Chuis
                Engine-->>Sumula: CONVERTE 2 CHUIS EM 0.5 PTO (Waza-ari) PARA O OPONENTE
            end
        else Transição para Solo
            Mesa->>Sumula: Dispara cronômetro de Ne Waza (60s)
            Sumula-->>Mesa: Alerta sonoro/visual aos 10s de inatividade e término aos 60s
        end
    end
    
    alt Tempo Esgotado com Empate
        Sumula->>Engine: Ativa Golden Score (1:00)
        Mesa->>Sumula: Primeiro ponto marcado
        Engine-->>Sumula: PONTO DE OURO! Declara vencedor imediato
    else Empate após Golden Score
        Sumula-->>Mesa: Abre painel de Hantei (Votação dos 5 Árbitros)
        Sumula->>DB: Consulta quebramentos de Tameshiwari dos dois atletas
        Mesa->>Sumula: Registra votos (Maioria 3 de 5)
    end
    
    Mesa->>Sumula: Finalizar Luta e Homologar
    Sumula->>DB: Atualiza vencedor e avança atleta na chave
```

---

## 6. Fluxo de Avaliação de Kata (Formas)

```mermaid
flowchart TD
    K_Ini([Atleta chamado ao Tatame]) --> K_Nome[Mesa registra o nome do Kata anunciado]
    K_Nome --> K_Exec[Atleta executa a forma]
    K_Exec --> K_Notas[Mesa digita notas individuais dos 3 a 5 árbitros de 0.0 a 10.0]
    K_Notas --> K_Calc[Sistema calcula a Média Aritmética simples exata]
    K_Calc --> K_Check{Houve empate na maior média da categoria?}
    
    K_Check -- Sim --> K_Desempate[Abre rodada de desempate: Atletas empatados executam NOVO Kata]
    K_Desempate --> K_NovasNotas[Registra novas notas individuais e calcula nova média]
    K_NovasNotas --> K_Classif
    
    K_Check -- Não --> K_Classif[Ordena atletas pela média final]
    K_Classif --> K_Fim([Classificação Homologada])
```

---

## 7. Fluxo de Avaliação de Tameshiwari (Quebramento)

```mermaid
flowchart TD
    T_Ini([Atleta convocado para Tameshiwari]) --> T_Declara[Declaração prévia da quantidade por técnica]
    T_Declara --> T_Tecnicas[Percorrer as 4 Oficinas: Seiken, Shuto, Hiji, Chusoku/Sokuto/Kakato]
    
    T_Tecnicas --> T_Exec[Árbitro autoriza: cronômetro de até 60 segundos]
    T_Exec --> T_Tentativa{Quebramento completado com sucesso?}
    
    T_Tentativa -- Sim --> T_Sucesso[Registra quantidade de unidades quebradas]
    T_Tentativa -- Não --> T_Falha[Registra falha ou quebra parcial: NOTA ZERO na oficina]
    
    T_Sucesso --> T_Proxima{Faltam técnicas?}
    T_Falha --> T_Proxima
    
    T_Proxima -- Sim --> T_Tecnicas
    T_Proxima -- Não --> T_Total[Calcula Somatório Geral de unidades válidas]
    T_Total --> T_Fim([Total arquivado para classificação e desempate no Kumite])
```
