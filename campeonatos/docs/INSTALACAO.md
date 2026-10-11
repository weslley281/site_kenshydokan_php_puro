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
# Configurações da Aplicação
APP_NAME="Campeonatos Kenshydokan Jutsu"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://campeonatos.kenshydokan.org.br

# Banco Operacional Exclusivo (campeonatos)
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=campeonatos
DB_USER=u515961161_kenshydokan
DB_PASS=Wesv@g28

# Banco Corporativo Raiz (Somente Leitura de Filiados)
MAIN_DB_HOST=127.0.0.1
MAIN_DB_PORT=3306
MAIN_DB_NAME=u515961161_kenshydokan
MAIN_DB_USER=u515961161_kenshydokan
MAIN_DB_PASS=Wesv@g28
```

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

Após a execução das migrações, o sistema contará com um usuário administrador padrão para o primeiro acesso:
- **E-mail**: `admin@kenshydokan.org.br`
- **Senha Inicial**: `Admin@Kenshy2026`
- **Ação Imediata**: Realizar login e alterar a senha de acesso no primeiro login por medidas de segurança.
