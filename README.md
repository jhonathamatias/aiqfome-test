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

### Usando Docker

```bash
make setup
make start
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

## Endpoints da API

### Autenticação

- **POST** `/api/v1/auth`  
  Autentica o usuário e retorna um token.

---

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