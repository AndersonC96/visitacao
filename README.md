# Formulario de Cadastro

Gerencie visitas de representantes e clientes de forma eficiente com o sistema de visitação. Este projeto permite o cadastro de usuários, controle de visitas e exportação de relatórios detalhados.

## 📋 Funcionalidades

1. Gerenciamento de Usuários
- Criação, edição e remoção de usuários.
- Visualização de usuários cadastrados com permissões administrativas.

2. Controle de Visitas
- Cadastro detalhado de visitas com informações personalizadas.
- Filtragem de visitas por data ou representante.

3. Exportação de Dados
- Geração de relatórios em formato Excel com filtros personalizados.

4. Interface Responsiva
- Design adaptado para dispositivos móveis e desktops.

## 🛠️ Tecnologias Utilizadas

1. Frontend
-- HTML5, CSS3, JavaScript
Bootstrap 5
Backend:
PHP 7+ com PSR-12
Banco de Dados:
MySQL
Bibliotecas/Dependências:
PhpSpreadsheet para geração de relatórios Excel
Composer para gerenciar dependências
- **PHP:** Linguagem de programação server-side.
- **MySQL:** Sistema de gerenciamento de banco de dados.
- **Bootstrap:** Framework front-end para desenvolvimento de interfaces web responsivas e mobile-first.
- **Composer:** Ferramenta de gerenciamento de dependências para PHP.

## Requisitos

- Servidor web com suporte ao PHP (versão 7.4 ou superior recomendada).
- MySQL (versão 5.7 ou superior recomendada).
- Composer para a instalação de dependências PHP.

## Instalação

1. Clone o repositório do projeto para o seu servidor local ou de hospedagem.
    git clone https://github.com/AndersonC96/Formulario-de-Cadastro.git

2. Navegue até o diretório do projeto e instale as dependências PHP com o Composer.
    cd projeto-formularios
    composer install

3. Crie um banco de dados MySQL e importe o esquema localizado em `Database/db.sql`.

4. Configure a conexão com o banco de dados editando o arquivo `db.php` com as suas credenciais de banco de dados.

5. Acesse o projeto através do navegador utilizando o endereço do seu servidor.

## Uso

Após a instalação, acesse a tela de login e entre com suas credenciais. Navegue pelas diversas páginas para gerenciar formulários e usuários. Use as funções administrativas para uma gestão avançada do sistema.