# MyFreela

MyFreela é uma aplicação web desenvolvida em Laravel, projetada para ajudar freelancers a gerenciar seus clientes, projetos e faturas de forma eficiente.

## ✨ Funcionalidades

* **Dashboard:** Visão geral com métricas importantes como total de projetos, valores faturados, valores a receber e projetos em andamento.
* **Gestão de Clientes:** Crie, edite, visualize e exclua clientes.
* **Gestão de Projetos:** Crie projetos e associe-os a clientes, defina o escopo, prazo e valor. Acompanhe o status de cada projeto (Pendente, Em Andamento, Concluído, Cancelado).
* **Geração de Faturas:** Gere faturas em PDF para projetos concluídos.
* **Autenticação de Usuários:** Sistema completo de login, registro e recuperação de senha.

## 🛠️ Tecnologias Utilizadas

* **Backend:** PHP 8.2+, Laravel 12+
* **Frontend:** Blade, Tailwind CSS, Alpine.js
* **Banco de Dados:** SQLite (padrão), com suporte para MySQL, MariaDB, PostgreSQL e SQL Server
* **Testes:** Pest/PHPUnit
* **Geração de PDF:** DomPDF

## 🚀 Instalação e Configuração

Siga os passos abaixo para configurar o ambiente de desenvolvimento local:

1.  **Clone o repositório:**
    ```bash
    git clone [https://github.com/Netero-mac/myfreela.git](https://github.com/Netero-mac/myfreela.git)
    cd myfreela
    ```

2.  **Instale as dependências do Composer:**
    ```bash
    composer install
    ```

3.  **Configure o arquivo de ambiente:**
    * Copie o arquivo de exemplo: `cp .env.example .env`
    * Gere a chave da aplicação: `php artisan key:generate`
    * Configure as variáveis de ambiente, especialmente a conexão com o banco de dados (`DB_CONNECTION`, `DB_DATABASE`, etc.) no arquivo `.env`.

4.  **Execute as Migrations:**
    Para criar as tabelas no banco de dados, execute:
    ```bash
    php artisan migrate
    ```

5.  **Instale as dependências do NPM e compile os assets:**
    ```bash
    npm install
    npm run dev
    ```

6.  **Inicie o servidor de desenvolvimento:**
    ```bash
    php artisan serve
    ```
    Agora você pode acessar a aplicação em `http://localhost:8000`.

## 🧪 Testes

Para executar os testes automatizados e garantir a qualidade do código, utilize o seguinte comando:
```bash
php artisan test
```

---

## 👤 Autor

**Marco Antonio Cadoso da Cruz Santos**

- **LinkedIn:** `https://linkedin.com/in/[LINKEDIN]` 
- **GitHub:** `https://github.com/Netero-mac`
