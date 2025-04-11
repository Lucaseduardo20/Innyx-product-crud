# 🛠️ Plataforma Innyx - Gestão de Produtos

Essa é a plataforma administrativa da **Innyx**, desenvolvida com foco em performance, organização e responsividade.  
A aplicação possui autenticação via JWT, testes unitários robustos, validações seguras e uma interface pensada no conceito **mobile-first**.

---

## 🚀 Tecnologias Utilizadas

### Frontend:
- Vue 3 + Composition API
- TypeScript
- Pinia (gerenciamento de estado)
- Vue Router
- Tailwind CSS
- Mobile-first design

### Backend:
- Laravel 11
- JWT Auth
- Laravel Data (DTOs)
- Service Pattern
- Form Request para validações
- PHPUnit com mocks para testes unitários
- Docker + Docker Compose

---

## 👥 Jornadas do Usuário

### 👑 Administrador
- Criar novo usuário
- Editar usuário
- Excluir usuário
- Resetar senha de qualquer usuário
- Criar e excluir categorias
- Ver todos os produtos da plataforma
- Editar e excluir produtos

### ��‍💼 Colaborador
- Criar produto
- Editar produto
- Excluir produto
- Ver produto

### 🔐 Todos os usuários
- Alterar a própria senha

---

## 📱 Responsividade

A plataforma é **totalmente responsiva**, com foco em **mobile-first**, se adaptando perfeitamente a qualquer tamanho de tela.

---

## 🧪 Testes

- Cobertura com PHPUnit
- Testes unitários utilizando mocks
- Código desacoplado com uso de services e DTOs

---

## ▶️ Como Rodar o Projeto

## Clone o repositório

```bash
git clone <https://github.com/Lucaseduardo20/Innyx-product-crud>
cd innyx-app
docker-compose up --build
cd ../innyx-frontend
yarn && yarn dev


🔐 Usuário seedado
 - Email: admin@admin.com

 - Senha: 123123

Faça login com esse usuário para explorar a plataforma com permissões administrativas.

Todos os usuários criados na plataforma iniciam com a senha "123123" com possibilidade de alteração direto na plataforma.