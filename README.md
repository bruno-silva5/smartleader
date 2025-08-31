# Sistema de Gerenciamento de Tarefas Multiempresa

## 🚀 Tecnologias Utilizadas

- **Backend**: Laravel 8
- **Frontend**: Vue.js
- **Autenticação**: JWT (tymon/jwt-auth)
- **Banco de Dados**: MySQL
- **Container**: Docker

## 💻 Funcionalidades Principais

### Autenticação e Segurança
- Sistema de registro e login com JWT
- Isolamento de dados por empresa (multitenancy)
- Proteção de rotas e endpoints

### Gerenciamento de Tarefas
- Criação, leitura, atualização e exclusão de tarefas (CRUD)
- Status e prioridade
- Sistema de notificações por e-mail

### Multitenancy
- Suporte completo a múltiplas empresas
- Isolamento de dados entre empresas
- Gerenciamento de usuários por empresa

## 🔧 Instalação e Configuração

### Requisitos
- Docker e Docker Compose
- Composer
- Node.js e NPM

### Passos para Instalação

1. Clone o repositório
```bash
git clone https://github.com/bruno-silva5/smartleader.git
cd smartleader
```

2. Iniciar o Projeto
```bash
cd back-end
cp .env.example .env
docker-compose up -d
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan jwt:secret
```

## 📡 API Endpoints

### Autenticação
- `POST /api/auth/register` - Registro de novo usuário
- `POST /api/auth/login` - Login de usuário
- `POST /api/auth/logout` - Logout de usuário
- `GET /api/auth/me` - Informações do usuário logado

### Tarefas
- `GET /api/tasks` - Lista todas as tarefas da empresa
- `POST /api/tasks` - Cria uma nova tarefa
- `GET /api/tasks/{id}` - Obtém detalhes de uma tarefa
- `PUT /api/tasks/{id}` - Atualiza uma tarefa
- `DELETE /api/tasks/{id}` - Remove uma tarefa

## 🔔 Notificações

O sistema envia notificações por e-mail nos seguintes casos:
- Criação de nova tarefa
- Conclusão de tarefa

As notificações são processadas de forma assíncrona utilizando filas (queues) para melhor performance.

## 👥 Regras de Negócio

- Usuários só podem acessar dados da própria empresa
- Todas as operações requerem autenticação
- Isolamento total de dados entre empresas

## 🔒 Segurança

- Autenticação JWT
- Isolamento de dados por tenant