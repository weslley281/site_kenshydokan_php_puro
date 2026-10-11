# MATRIZ DE PERMISSÕES E POLÍTICA DE AUTORIZAÇÃO
## Sistema de Campeonatos e Súmulas Kenshydokan Jutsu
**Abordagem**: Role-Based Access Control (RBAC) Verificado no Servidor (PHP 8.4)

---

## 1. Perfis de Usuários (Roles)

O sistema define os seguintes papéis funcionais:

1. **Administrador (`admin`)**:
   - Controle executivo e irrestrito da plataforma;
   - Configuração de campeonatos, categorias, regras e identidade visual;
   - Gestão de contas de operadores e permissões de acesso;
   - Sorteio oficial de chaves e autorização de remanejamentos;
   - Homologação final de resultados, publicação de pódios e emissão de certificados em lote;
   - Acesso exclusivo a trilhas e relatórios de auditoria e segurança.
2. **Operador de Tatame / Mesa (`operador_tatame`)**:
   - Responsável pela mesa de controle de uma área de competição específica;
   - Operação em tempo real do cronômetro oficial e pontuação da súmula de Kumite;
   - Lançamento de notas da mesa de Kata e quebramentos de Tameshiwari;
   - Registro de pesagens oficiais e chamada de atletas na fila do tatame;
   - Encerramento de luta no tatame (sujeito à homologação administrativa).
3. **Árbitro Oficial (`arbitro_mesa`)**:
   - Acesso de conferência e assinatura eletrônica das súmulas;
   - Registro de votação no Hantei e consulta técnica a regras e súmulas.
4. **Público / Telão de Tatame (`observador`)**:
   - Acesso somente leitura;
   - Visualização de chaves atualizadas em tempo real, painel de chamadas e placar público do tatame.

---

## 2. Matriz de Autorização por Funcionalidade

| Módulo / Operação | Administrador | Operador de Tatame | Árbitro de Mesa | Observador / Público |
| :--- | :---: | :---: | :---: | :---: |
| **Criar / Editar / Arquivar Campeonatos** | **Sim** | Não | Não | Não |
| **Configurar Categorias e Critérios** | **Sim** | Não | Não | Não |
| **Gerenciar Usuários e Senhas** | **Sim** | Não | Não | Não |
| **Inscrever Atleta (Snapshot Filiado)** | **Sim** | Sim (Se autorizado) | Não | Não |
| **Registrar Pesagem na Balança** | **Sim** | **Sim** | Não | Não |
| **Remanejar Atleta Reprovado no Peso** | **Sim** (Com justificativa) | Não | Não | Não |
| **Gerar Sorteio e Árvore de Chaves** | **Sim** | Não | Não | Não |
| **Reordenar Fila de Lutas do Tatame** | **Sim** | **Sim** (No tatame dele) | Não | Não |
| **Operar Cronômetro e Pontos Kumite** | **Sim** | **Sim** (No tatame dele) | Não | Não |
| **Lançar Notas de Kata** | **Sim** | **Sim** | Não | Não |
| **Lançar Quebras de Tameshiwari** | **Sim** | **Sim** | Não | Não |
| **Finalizar Luta no Tatame** | **Sim** | **Sim** | Não | Não |
| **Homologação Oficial de Resultado** | **Sim** | Não | Não | Não |
| **Retificar Súmula Homologada** | **Sim** (Com justificativa) | Não | Não | Não |
| **Emitir Certificados Oficiais em PDF** | **Sim** | Não | Não | Não |
| **Validar Autenticidade de Certificado** | **Sim** | **Sim** | **Sim** | **Sim** (Pública) |
| **Visualizar Telão e Placar do Tatame** | **Sim** | **Sim** | **Sim** | **Sim** |
| **Consultar Relatórios de Auditoria** | **Sim** | Não | Não | Não |

---

## 3. Diretrizes de Segurança no Servidor

1. **Validação Obrigatória no Backend**: A interface oculta botões inacessíveis para o perfil, porém **toda e qualquer ação é validada no servidor** em `AuthorizationPolicy.php` antes de qualquer execução SQL. A manipulação de parâmetros no navegador (como IDs de formulários ou chamadas AJAX forjadas) resultará em HTTP 403 (*Forbidden*).
2. **Proteção de Súmulas Homologadas**: Após a marcação de `homologado = 1`, nenhuma alteração de pontuação é permitida por operadores de mesa. Caso ocorra contestação legítima julgada pela Comissão de Arbitragem, apenas o Administrador poderá acionar a rotina de **Retificação Auditada**, informando obrigatoriamente a justificativa técnica que será gravada na tabela `campeonato_auditoria`.
3. **Concorrência entre Operadores**: Duas conexões não podem operar o mesmo tatame simultaneamente. Ao abrir a súmula, o sistema adquire um bloqueio temporário (*lock* operacional) associado ao usuário daquele tatame.
