# Formulario de Cadastro

Gerencie visitas de representantes e clientes de forma eficiente com o sistema de visitação. Este projeto permite o cadastro de usuários, controle de visitas e exportação de relatórios detalhados.

## 📋 Funcionalidades

### 1. **Gerenciamento de Usuários**
- Criação, edição e remoção de usuários.
- Visualização de usuários cadastrados com permissões administrativas.

### 2. **Controle de Visitas**
- Cadastro detalhado de visitas com informações personalizadas.
- Filtragem de visitas por data ou representante.

### 3. **Exportação de Dados**
- Geração de relatórios em formato Excel com filtros personalizados.

### 4. **Interface Responsiva**
- Design adaptado para dispositivos móveis e desktops.

## 🛠️ Tecnologias Utilizadas

### 1. **Frontend**
- HTML5, CSS3, JavaScript
- Bootstrap 5

### 2. **Backend**
- PHP 8+ com PSR-12

### 3. **Banco de Dados**
- MySQL

### 4. **Bibliotecas/Dependências**
- PhpSpreadsheet para geração de relatórios Excel
- Composer para gerenciar dependências

## Estrutura do Projeto

```bash
visitacao/
├── CSS/                  # Arquivos de estilos personalizados
├── config/               # Arquivos de configuração (ex.: conexões de banco)
├── controllers/          # Lógica de controle da aplicação
├── models/               # Modelos para manipulação de dados
├── public/               # Arquivos públicos acessíveis pela web
├── views/                # Templates e componentes reutilizáveis
├── vendor/               # Dependências gerenciadas pelo Composer
├── index.php             # Entrada principal da aplicação
├── composer.json         # Configuração do Composer
├── .gitignore            # Arquivos ignorados pelo Git
└── README.md             # Documentação do projeto
```

## 🚀 Como Usar

### 1. **Pré-requisitos**

- PHP 7+
- MySQL
- Composer

### 2. **Instalação**

#### 2.1. **Clone o repositório**

```bash
git clone https://github.com/AndersonC96/visitacao.git
cd visitacao
```

#### 2.2. **Instale as dependências**

```bash
composer install
```

#### 2.3. **Configure o arquivo de banco de dados**

- Renomeie o arquivo `config/db.example.php` para `config/db.php`.
- Insira suas credenciais de banco de dados no arquivo.

#### 2.4. **Importe o banco de dados**

- Encontre o arquivo database.sql (caso disponível) e importe no seu MySQL:

```bash
mysql -u root -p < database.sql
```

### 3. **3. Como Executar**

- Inicie o servidor local

```bash
php -S localhost:8000 -t public/
```

- Acesse no navegador

```bash
http://localhost:8000
```

## 👨‍💻 Desenvolvimento

### Convenções de Código

- Padrão PSR-12 para organização e formatação do código PHP.
- Nomes de variáveis e funções seguem o padrão camelCase.

## 📈 Melhorias Planejadas

- Implementação de autenticação via JWT.
- Adicionar testes automatizados com PHPUnit.
- Criar uma API REST para integração com outros sistemas.
- Melhorar o design e adicionar suporte para temas.