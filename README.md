# Skeleton Hyperf

Aplicação de exemplo utilizando o framework [Hyperf](https://hyperf.io/), pronta para servir como ponto de partida para novos projetos PHP modernos, performáticos e baseados em microserviços.

## Requisitos

- **PHP >= 8.1**
- Extensões PHP: Swoole (>= 5.0) ou Swow (>= 1.3), JSON, Pcntl, OpenSSL, PDO, Redis, Protobuf
- **Docker** (recomendado para desenvolvimento)
- **Composer**

## Arquitetura

O projeto adota princípios da Arquitetura Limpa (Clean Architecture), organizando o código em camadas bem definidas para garantir baixo acoplamento, alta coesão e facilidade de manutenção. Os principais componentes são:

- **Domain (Domínio):** Entidades e regras de negócio
- **Application (Aplicação):** Serviços de aplicação e orquestração dos casos de uso.
- **Infrastructure (Infraestrutura):** Implementações de repositórios, integrações externas (banco de dados, Redis, APIs, etc).
- **Interface (Apresentação):** Controllers, rotas, middlewares e validações.

Outros pontos:

- **Framework:** Hyperf (PHP moderno, orientado a alto desempenho e corrotinas)
- **Banco de Dados:** PostgreSQL
- **Cache:** Redis
- **Gerenciamento de dependências:** Composer
- **Containers:** Docker e Docker Compose
- **Migrations e Seeders:** Comandos Hyperf
- **Testes:** PHPUnit
- **Validação:** Middlewares dedicados
- **Autenticação:** Middleware dedicado para rotas protegidas

## Instalação

#### Usando Makefile

```bash
  make setup
```

#### Usando Docker Compose diretamente
 
```bash
  cp .env.example .env
  docker build . -f docker/dev.Dockerfile -t hyperf-dev-server --no-cache
  docker run --dns 8.8.8.8 --rm -v ".:/opt/www" hyperf-dev-server composer install
```
## Testes

Execute os testes automatizados com o comando:

```bash
  make test
```
## Migrations

Para aplicar as migrations e criar as tabelas no banco de dados, execute:

```bash
  make migrate
```

## Seed

Para popular o banco de dados com dados de exemplo, execute:

```bash
  make seed
```

##  Inicialização do Servidor
Acesse `http://localhost:9501` após a inicialização.
#### Usando Makefile
  ```bash
    make start
```
#### Usando Docker Compose diretamente
  ```bash
    docker-compose up -d
  ```
## Endpoints da API

### Autenticação
Autentica o usuário e retorna um token.
- **POST** `/api/v1/auth`  
**Body:**
  - `email` (email, obrigatório): admin@admin.com
  - `password` (string, obrigatório): 123456

---
> Todas as rotas de clientes e favoritos exigem autenticação via middleware utilizando **JWT Bearer Token**.

> Para acessar essas rotas, envie o header:
>
Use o cabeçalho `Authorization: Bearer <token>` nas requisições autenticadas.

### Clientes (protegido por autenticação)

- **POST** `/api/v1/clients`  
  Cria um novo cliente.  
  **Body:**  
  - `name` (string, obrigatório)  
  - `email` (email, obrigatório)

- **GET** `/api/v1/clients/{id}`  
  Consulta cliente por ID (UUID).  
  **URL param:**  
  - `id` (uuid, obrigatório)

- **PUT** `/api/v1/clients/{id}`  
  Atualiza dados do cliente.  
  **URL param:**  
  - `id` (uuid, obrigatório)  
  **Body:**  
  - `name` (string, opcional)  
  - `email` (email, opcional)

- **DELETE** `/api/v1/clients/{id}`  
  Remove cliente.  
  **URL param:**  
  - `id` (uuid, obrigatório)

- **GET** `/api/v1/clients`  
  Lista clientes.  
  **Query param:**  
  - `limit` (integer, opcional)

---

### Favoritos do Cliente

- **POST** `/api/v1/clients/{id}/favorites`  
  Adiciona produto favorito ao cliente.  
  **URL param:**  
  - `id` (uuid, obrigatório)  
  **Body:**  
  - `product_id` (integer, obrigatório)

- **POST** `/api/v1/clients/{id}/favorites/batch`  
  Adiciona vários produtos favoritos.  
  **URL param:**  
  - `id` (uuid, obrigatório)  
  **Body:**  
  - `product_ids` (array de inteiros, obrigatório)

- **GET** `/api/v1/clients/{id}/favorites`  
  Lista favoritos do cliente.  
  **URL param:**  
  - `id` (uuid, obrigatório)  
  - `limit` (integer, opcional)

---

> Todas as rotas de clientes e favoritos exigem autenticação via middleware.