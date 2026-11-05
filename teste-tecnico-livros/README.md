# Gerenciamento de Livros

Projeto desenvolvido como parte de um desafio técnico. A aplicação permite cadastrar e gerenciar livros, autores e assuntos, além de consultar um relatório de livros agrupados por autor, com opção de exportação em PDF.

## Tecnologias

- PHP 8.4
- Laravel 12
- PostgreSQL 16
- SQLite (testes automatizados)
- Bootstrap 5
- Docker e Docker Compose
- PHPUnit
- DomPDF
- Vite

## Funcionalidades desenvolvidas

- Cadastro, edição, consulta e exclusão de livros.
- Cadastro, edição, consulta e exclusão de autores.
- Cadastro, edição, consulta e exclusão de assuntos.
- Associação de um livro a vários autores e assuntos.
- Listagem dos registros com paginação.
- Validação dos dados dos formulários.
- Tratamento de erros nas operações com o banco de dados.
- Relatório de livros agrupados por autor.
- Exportação do relatório em PDF.
- Formatação de valores monetários.

## Banco de dados

O modelo de dados utiliza as seguintes entidades:

- **Livro:** armazena os dados dos livros, incluindo título, editora, edição, ano de publicação e valor.
- **Autor:** armazena os nomes dos autores.
- **Assunto:** armazena as descrições dos assuntos.

Os relacionamentos entre livros e autores, e entre livros e assuntos, são muitos-para-muitos, implementados por meio das tabelas intermediárias `livro_autor` e `livro_assunto`.

Foram utilizadas chaves estrangeiras e restrições de integridade referencial para manter a consistência dos dados.

O relatório utiliza a VIEW `vw_livros_por_autor` como fonte de consulta.

## Como executar

O projeto utiliza Docker Compose para executar a aplicação, o banco de dados PostgreSQL, o servidor Nginx e o Vite.

### Pré-requisitos

- Docker Desktop
- Git

### 1. Clonar o projeto

```bash
git clone https://github.com/herlandio/teste-tecnico-livros.git
cd teste-tecnico-livros
```

### 2. Configurar o ambiente

Copie o arquivo de configuração:

```bash
cp .env.example .env
```

Confira as configurações do banco de dados no arquivo `.env`:

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

Após concluir as etapas anteriores, acesse:

[http://localhost:8080](http://localhost:8080)

O serviço Vite é iniciado pelo Docker Compose e disponibiliza os recursos de frontend durante a execução da aplicação.

## Relatório

O relatório pode ser acessado pelo menu da aplicação ou diretamente pelas URLs:

- **HTML:** http://localhost:8080/relatorios/livros-por-autor
- **PDF:** http://localhost:8080/relatorios/livros-por-autor/pdf

Os dados são consultados por meio da VIEW `vw_livros_por_autor`, agrupando os livros por autor. Um mesmo livro pode aparecer em mais de um grupo quando possui múltiplos autores associados.

## Testes automatizados

Os testes foram implementados com PHPUnit e utilizam SQLite em memória, separado do PostgreSQL utilizado pela aplicação.

Essa configuração permite executar os cenários de teste isoladamente, sem alterar os dados do banco principal.

Para executar todos os testes:

```bash
docker compose exec app php artisan test
```
