# BookshelfApi\_2

![PHP](https://img.shields.io/badge/PHP-8.1-blue)
![Slim](https://img.shields.io/badge/Slim-4.x-lightgrey)
![Docker](https://img.shields.io/badge/Docker-Compose-blue)
![Architecture](https://img.shields.io/badge/Architecture-Hexagonal-green)

## Índice

* [Descrição](#descricao)
* [Arquitetura](#arquitetura)
* [Tecnologias](#tecnologias)
* [Pré‑requisitos](#pre-requisitos)
* [Instalação](#instalação)
* [Como Rodar](#como-rodar)
* [Endpoints](#endpoints)
* [Estrutura de Diretórios](#estrutura-de-diretórios)

---

<h2 id="descricao">Descrição</h2>

**BookshelfApi\_2** é uma API RESTful para gerenciar um catálogo de livros.
Implementada em PHP com [Slim Framework 4](https://www.slimframework.com/), organizada segundo os princípios da **Arquitetura Hexagonal** (Ports & Adapters) e conteinerizada com Docker Compose.

---
<h2 id="arquitetura">Arquitetura</h2>

* **Domain**: Entidades e Portas (interfaces de repositório).
* **Application**: Casos de uso (regras de negócio).
* **Infrastructure**: Adaptadores (persistência via PDO/MySQL).
* **Interfaces (HTTP)**: Controllers que expõem as rotas Slim.

A app carrega variáveis de ambiente via `vlucas/phpdotenv` e injeta dependências com [PHP‑DI](https://php-di.org/).

---

<h2 id="tecnologias">Tecnologias</h2>

* PHP 8.1‑CLI
* [Slim Framework 4](https://www.slimframework.com/)
* MySQL 8.0
* Docker & Docker Compose
* [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv)
* [php-di/php-di](https://github.com/PHP-DI/PHP-DI)

---
<h2 id="pre-requisitos">Pré‑requisitos</h2>

* [Docker](https://www.docker.com/) (>= 20.x)
* [Docker Compose](https://docs.docker.com/compose/) (>= 2.x)

---

<h2 id="instalacao">Instalação</h2>

1. **Clone o repositório**

   ```bash
   git clone https://github.com/1134867JP/BookshelfApi_2.git
   cd BookshelfApi_2
   ```

2. **Copie o arquivo de exemplo de ambiente**

   ```bash
   cp .env.example .env
   ```

3. **(Opcional) Ajuste as credenciais em `.env`**

   ```dotenv
   DB_HOST=db
   DB_NAME=bookshelf
   DB_USER=shelfuser
   DB_PASS=shelfpass
   MYSQL_ROOT_PASSWORD=rootpass
   ```

---

<h2 id="como-rodar">Como Rodar</h2>

Execute na raiz do projeto:

```bash
# Limpa containers, redes e volumes antigos
docker compose down --volumes

# Constrói e inicia todos os serviços em background
docker compose up --build -d
```

* A API ficará disponível em:
  `http://localhost:8081/books`

* Para ver logs em tempo real:

  ```bash
  docker compose logs -f app
  ```

* Para parar tudo:  
   ```bash
   docker compose down
   ````

---

<h2 id="endpoints">Endpoints</h2>

|   Método | Rota          | Descrição                                   |
| -------: | ------------- | ------------------------------------------- |
|    `GET` | `/books`      | Lista todos os livros                       |
|    `GET` | `/books/{id}` | Retorna um livro específico por ID          |
|   `POST` | `/books`      | Cria um novo livro (JSON)                   |
|    `PUT` | `/books/{id}` | Atualiza dados de um livro existente (JSON) |
| `DELETE` | `/books/{id}` | Remove um livro                             |

### Exemplo Payload

```json
{
  "title": "O Senhor dos Anéis",
  "author": "J.R.R. Tolkien",
  "published_year": 1954
}
```

### Exemplos com `curl`

```bash
# Listar
curl http://localhost:8081/books

# Criar
curl -X POST http://localhost:8081/books \
     -H "Content-Type: application/json" \
     -d '{"title":"1984","author":"George Orwell","published_year":1949}'

# Atualizar
curl -X PUT http://localhost:8081/books/1 \
     -H "Content-Type: application/json" \
     -d '{"title":"Duna","author":"Frank Herbert","published_year":1965}'

# Excluir
curl -X DELETE http://localhost:8081/books/1
```

---

<h2 id="estrutura-de-diretórios">Estrutura de Diretórios</h2>

```
.
├── .env.example
├── Dockerfile
├── docker-compose.yml
├── init.sql
├── composer.json
├── README.md
├── public/
│   └── index.php              # Entry‑point HTTP
├── src/
│   ├── bootstrap.php          # Configuração de DI e Middlewares
│   ├── Domain/
│   │   ├── Entity/Book.php
│   │   └── Repository/BookRepositoryInterface.php
│   ├── Application/
│   │   └── UseCase/
│   │       ├── CreateBook.php
│   │       ├── ListBooks.php
│   │       ├── UpdateBook.php
│   │       └── DeleteBook.php
│   ├── Infrastructure/
│   │   └── Persistence/
│   │       ├── PDOConnection.php
│   │       └── BookRepositoryMySQL.php
│   └── Interfaces/
│       └── Http/
│           └── BookController.php
└── config/
    └── settings.php           # Configurações de DB
```
