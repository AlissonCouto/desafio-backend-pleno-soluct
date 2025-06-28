# desafio-backend-pleno-soluct

API RESTful para Gestão de Tarefas desenvolvida como parte do processo seletivo para Desenvolvedor Backend Pleno (Laravel/PHP) na Soluct.

---

## 🎯 **Funcionalidades**

- Autenticação de usuários (registro, login, logout) via Sanctum
- CRUD completo de tarefas
- Histórico de alterações nas tarefas
- Cada usuário gerencia apenas suas tarefas
- Listagem de tarefas com filtros, paginação e ordenação
- Tratamento robusto de erros
- Versionamento de API (`/api/v1`)
- Cache de listagens para performance
- Docker (App + Nginx + PostgreSQL)

---

## 🚀 **Tecnologias Utilizadas**

- PHP 8.2
- Laravel 12
- PostgreSQL
- Docker + Docker Compose
- Nginx

---

## ⚙️ **Como rodar localmente**

### **Pré-requisitos**

- Docker
- Docker Compose

### **Passos**

```bash
# Clone este repositório
git clone https://github.com/AlissonCouto/desafio-backend-pleno-soluct.git
cd desafio-backend-pleno-soluct

# Copie o .env
cp .env.example .env

# Configuração do banco de dados no .env
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=laraveluser
DB_PASSWORD=secret

# Suba os containers
docker-compose up -d --build

# Entre no container app
docker exec -it laravel_app bash

### Resolvendo problemas comuns de permissão

# Para garantir permissões corretas no projeto, execute:

docker exec -it laravel_app bash
find * -type d -exec chmod 755 {} \;
find * -type f -exec chmod 644 {} \;
chmod -R 775 storage bootstrap/cache
exit

# Instale as dependências (se ainda não instaladas)
composer install

# Gere a key
php artisan key:generate

# Rode as migrations
php artisan migrate

# Saia do container
exit
```

# 📚 Documentação dos Endpoints

## **Base URL**

```
http://localhost:8080/api/v1/
```

---

### 🔐 **Autenticação**

#### **POST /register**

Cria um novo usuário.

**Payload exemplo:**

```json
{
  "name": "João Silva",
  "email": "joao@example.com",
  "password": "password",
  "password_confirmation": "password"
}
```

**Retorno esperado:**

```json
{
  "ok": true,
  "message": "Usuário cadastrado com sucesso",
  "user": {
    "id": 1,
    "name": "João Silva",
    "email": "joao@example.com"
  },
  "token": "seu-token-aqui"
}
```

---

#### **POST /login**

Realiza login do usuário.

**Payload exemplo:**

```json
{
  "email": "joao@example.com",
  "password": "password"
}
```

**Retorno esperado:**

```json
{
  "ok": true,
  "token": "seu-token-aqui"
}
```

---

#### **POST /logout**

Realiza logout do usuário autenticado.

**Headers:**

```
Authorization: Bearer seu-token-aqui
```

**Retorno esperado:**

```json
{
  "ok": true,
  "message": "Desconectado com sucesso"
}
```

---

### ✅ **Tarefas**

Todas as rotas de tarefas requerem **Bearer Token** no header.

---

#### **GET /tasks**

Lista tarefas do usuário autenticado, com filtros, paginação e ordenação.

**Parâmetros de filtro (query string):**

- `status`: pendente, em_andamento, completed, cancelled
- `title`: busca textual parcial
- `created_from` e `created_to`: datas de criação (YYYY-MM-DD)
- `due_from` e `due_to`: datas de vencimento (YYYY-MM-DD)
- `order_by`: campo de ordenação (ex: created_at)
- `direction`: asc ou desc
- `per_page`: quantidade por página

**Exemplo de chamada completa:**

```
GET /api/v1/tasks?status=pendente&per_page=1
```

**Retorno esperado:**

```json
{
  "ok": true,
  "message": "Tarefas listadas com sucesso",
  "tasks": {
    "current_page": 1,
    "data": [ ... ]
  }
}
```

---

#### **POST /tasks**

Cria uma nova tarefa.

**Payload exemplo:**

```json
{
  "title": "Estudar Docker",
  "description": "Finalizar estudo de dockerização de projetos Laravel",
  "status": "pendente",
  "due_date": "2025-06-30"
}
```

**Retorno esperado:**

```json
{
  "ok": true,
  "message": "Tarefa criada com sucesso",
  "task": {
    "id": 1,
    "title": "Estudar Docker"
  }
}
```

---

#### **GET /tasks/{id}**

Exibe uma tarefa específica.

**Retorno esperado:**

```json
{
  "ok": true,
  "message": "Tarefa encontrada com sucesso",
  "task": {
    "id": 1,
    "title": "Estudar Docker"
  }
}
```

---

#### **PUT /tasks/{id}**

Atualiza uma tarefa.

**Payload exemplo:**

```json
{
  "title": "Estudar Docker atualizado",
  "description": "Atualizando descrição",
  "status": "em_andamento",
  "due_date": "2025-07-01"
}
```

**Retorno esperado:**

```json
{
  "ok": true,
  "message": "Tarefa atualizada com sucesso",
  "task": {
    "id": 1,
    "title": "Estudar Docker atualizado"
  }
}
```

---

#### **DELETE /tasks/{id}**

Deleta uma tarefa.

**Retorno esperado:**

```json
{
  "ok": true,
  "message": "Tarefa deletada com sucesso"
}
```

---

### 🕒 **Histórico de Alterações**

#### **GET /tasks/{id}/history**

Lista o histórico de alterações de uma tarefa específica.

**Retorno esperado:**

```json
{
  "ok": true,
  "message": "Histórico listado com sucesso",
  "history": [
    {
      "id": 1,
      "field_changed": "status",
      "old_value": "pendente",
      "new_value": "em_andamento",
      "created_at": "2025-06-27T12:00:00.000000Z"
    }
  ]
}
```

---

### ⚠️ **Observação**

✔️ **Todas as rotas de tarefas exigem autenticação.**  
✔️ Utilize o token retornado no login como **Bearer Token** no header.
