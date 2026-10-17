# GUIA DE INSTALAÇÃO E IMPLANTAÇÃO
## Sistema de Campeonatos e Súmulas Kenshydokan Jutsu
**Subdomínio Oficial**: `campeonatos.kenshydokan.org.br`  
**Ambientes Suportados**: WAMP64 Local (Windows) e Servidor de Produção (Hostinger / Apache cPanel)

---

## 1. Requisitos do Servidor

- **PHP**: Versão 8.2 ou superior (testado e homologado em **PHP 8.4.13**).
- **Extensões PHP Obrigatórias**:
  - `mysqli` (comunicação nativa com o MariaDB);
  - `json` (serialização de árvores de chaves e auditoria);
  - `mbstring` (manipulação de strings multibyte e relatórios FPDF);
  - `openssl` (geração de tokens criptográficos e hashes de certificados).
- **Banco de Dados**: MariaDB 10.4+ ou MySQL 8.0+ com suporte a transações `InnoDB` e charset `utf8mb4`.
- **Servidor Web**: Apache 2.4+ com módulo `mod_rewrite` habilitado para suporte a URLs amigáveis.

---

## 2. Configuração do Banco de Dados no MariaDB

1. Acesse o console do MariaDB ou phpMyAdmin:
```sql
-- 1. Criar o banco de dados dedicado aos campeonatos
CREATE DATABASE IF NOT EXISTS `campeonatos` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- 2. Conceder permissões ao usuário da aplicação (exemplo de produção)
GRANT ALL PRIVILEGES ON `campeonatos`.* TO 'u515961161_kenshydokan'@'localhost';
FLUSH PRIVILEGES;
```
2. Importe o script de estrutura localizado em:
   `campeonatos/database/migrations/001_create_database_and_tables.sql`.

---

## 3. Configuração das Variáveis de Ambiente (`.env`)

Copie o arquivo de exemplo para a raiz do diretório `campeonatos/`:
```bash
copy campeonatos\.env.example campeonatos\.env
```

Edite o arquivo `campeonatos/.env` com as configurações do seu ambiente:

```ini
# Debug deve permanecer desativado em produção.
APP_DEBUG=false

# Banco operacional dos campeonatos
DB_HOST=127.0.0.1
DB_NAME_CAMPEONATOS=nome_do_banco_campeonatos
DB_USER=usuario_do_banco
DB_PASS=senha_do_banco

# Banco do site principal (consulta de filiados)
MAIN_DB_NAME=nome_do_banco_principal
MAIN_DB_USER=usuario_do_banco_principal

# Conta criada no primeiro acesso, somente se ainda não houver admin
ADMIN_EMAIL=admin@seu-dominio.com
ADMIN_PASSWORD=defina-uma-senha-forte-com-12-caracteres
```

O sistema usa a porta `3306` por padrão e compartilha host e senha entre os dois bancos. Se o provedor usar valores diferentes, adicione somente os ajustes necessários: `DB_PORT`, `MAIN_DB_HOST`, `MAIN_DB_PORT` e/ou `MAIN_DB_PASS`. `APP_NAME`, `APP_ENV` e `APP_URL` também são opcionais; os padrões da aplicação já correspondem ao ambiente de produção.

---

## 4. Configuração do Apache e Subdomínio

### 4.1 Em Produção (Hostinger / cPanel)
1. No painel de controle da Hostinger, crie o subdomínio:
   - **Nome do Subdomínio**: `campeonatos`
   - **Domínio**: `kenshydokan.org.br`
   - **Pasta Raiz (DocumentRoot)**: `public_html/site_kenshydokan_php_puro/campeonatos/public`
2. Certifique-se de que o certificado SSL (Let's Encrypt / Hostinger SSL) está emitido e ativo para `campeonatos.kenshydokan.org.br`.

### 4.2 Em Desenvolvimento Local (WAMP64)

#### Opção A — Acesso Direto pela URL da Pasta
Você pode acessar diretamente pelo navegador em:
`http://localhost/site_kenshydokan_php_puro/campeonatos/public/`

#### Opção B — Virtual Host Local (Recomendado)
No arquivo `C:\wamp64\bin\apache\apacheX.X.X\conf\extra\httpd-vhosts.conf`:
```apache
<VirtualHost *:80>
    ServerName campeonatos.kenshydokan.local
    DocumentRoot "d:/WAMP64/www/site_kenshydokan_php_puro/campeonatos/public"
    <Directory "d:/WAMP64/www/site_kenshydokan_php_puro/campeonatos/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```
E adicione no arquivo `C:\Windows\System32\drivers\etc\hosts`:
```
127.0.0.1 campeonatos.kenshydokan.local
```

---

## 5. Permissões de Diretórios de Armazenamento

Certifique-se de que o servidor web possui permissão de leitura e escrita nas seguintes pastas de `storage`:
- `campeonatos/storage/logs/` (logs da aplicação e erros);
- `campeonatos/storage/uploads/` (logotipos e documentos);
- `campeonatos/storage/generated/` (certificados e súmulas temporárias geradas pelo FPDF).

---

## 6. Primeiro Acesso Administrativo

No primeiro acesso, depois de criar as tabelas, o sistema cria um usuário administrador se ainda não existir nenhum. Configure `ADMIN_EMAIL` e `ADMIN_PASSWORD` no `.env` antes de abrir o sistema. A senha deve ter pelo menos 12 caracteres; escolha uma senha forte e não a reutilize em outros serviços. Se já houver um administrador, o sistema não altera nem redefine a conta.
