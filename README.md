# Gerenciamento de Livros

Aplicação desenvolvida como parte de um desafio técnico, com o objetivo de gerenciar livros, autores e assuntos. O sistema conta com operações de cadastro, edição, consulta e exclusão, além de um relatório que reúne os livros por autor e permite a exportação em PDF.

## Tecnologias utilizadas

- PHP 8.4
- Laravel 12
- PostgreSQL 16
- SQLite (testes automatizados)
- Bootstrap 5
- Docker e Docker Compose
- PHPUnit
- DomPDF
- Vite

## Funcionalidades

- Cadastro, edição, consulta e exclusão de livros, autores e assuntos.
- Associação de vários autores e assuntos a um mesmo livro.
- Listagem de registros com paginação.
- Validação dos dados informados nos formulários.
- Tratamento de erros durante as operações com o banco de dados.
- Relatório de livros agrupados por autor.
- Exportação do relatório em PDF.
- Formatação de valores monetários.

## Estrutura do banco de dados

O banco de dados é composto por três entidades principais:

- **Livro:** armazena informações como título, editora, edição, ano de publicação e valor.
- **Autor:** armazena os nomes dos autores.
- **Assunto:** armazena as descrições dos assuntos.

Um livro pode estar associado a vários autores e assuntos. Esses relacionamentos são representados pelas tabelas intermediárias `livro_autor` e `livro_assunto`.

Também foram utilizadas chaves estrangeiras e restrições de integridade referencial para manter a consistência dos dados.

Para gerar o relatório, foi criada a VIEW `vw_livros_por_autor`, utilizada como fonte das consultas.

## Como executar o projeto

A aplicação utiliza Docker Compose para facilitar a configuração e execução do ambiente, que inclui PHP, PostgreSQL, Nginx e Vite.

### Pré-requisitos

- Docker Desktop
- Git

### 1. Clonar o repositório

```bash
git clone https://github.com/herlandio/teste-tecnico-livros.git
cd teste-tecnico-livros
```

### 2. Configurar o ambiente

Copie o arquivo de configuração:

```bash
cp .env.example .env
```

Confira se as configurações do banco de dados no arquivo `.env` estão corretas:

```env
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=livros
DB_USERNAME=livros_user
DB_PASSWORD=livros_password
```

### 3. Iniciar os containers

```bash
docker compose up -d --build
```

### 4. Instalar as dependências PHP

```bash
docker compose exec app composer install
```

### 5. Gerar a chave da aplicação

```bash
docker compose exec app php artisan key:generate
```

### 6. Executar as migrations

```bash
docker compose exec app php artisan migrate
```

### 7. Acessar a aplicação

Com o ambiente configurado, acesse:

http://localhost:8080

O Vite também é executado pelo Docker Compose, disponibilizando os recursos de frontend durante o desenvolvimento.

## Relatório de livros por autor

O relatório pode ser acessado pelo menu da aplicação ou diretamente pelos seguintes endereços:

- **HTML:** http://localhost:8080/relatorios/livros-por-autor
- **PDF:** http://localhost:8080/relatorios/livros-por-autor/pdf

Os dados são obtidos por meio da VIEW `vw_livros_por_autor` e organizados por autor. Quando um livro possui mais de um autor associado, ele aparece nos respectivos grupos do relatório.

## Testes automatizados

Os testes foram desenvolvidos com PHPUnit e utilizam SQLite em memória, mantendo o ambiente de testes separado do PostgreSQL utilizado pela aplicação.

Dessa forma, os testes podem ser executados sem interferir nos dados do banco principal.

Para executar:

```bash
docker compose exec app php artisan test
```