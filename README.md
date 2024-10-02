# Formulario de Cadastro

O Projeto de Criação de Formulários é uma aplicação web desenvolvida com PHP, MySQL, Bootstrap e Composer. A aplicação permite aos usuários criar, visualizar, editar e remover formulários de forma intuitiva e eficiente. Com uma interface amigável e recursos de gestão de usuários, incluindo funções administrativas, o sistema oferece uma solução completa para o gerenciamento de formulários.

## Funcionalidades

- **Tela de Login:** Acesso seguro ao sistema através de autenticação de usuário.
- **Página Home:** Visão geral e acesso rápido às principais funcionalidades do sistema.
- **Criação de Formulários:** Interface para a criação de novos formulários com campos personalizáveis.
- **Visualização de Formulários:** Lista todos os formulários criados com opções para visualização, edição e remoção.
- **Exportação de Formulários:** Permite aos usuários exportar os dados dos formulários cadastrados.
- **Gestão de Usuários:** Criação e visualização de usuários, com opções de edição e remoção. Recursos de administração disponíveis apenas para usuários com nível administrador.
- **Logout:** Opção para sair do sistema de forma segura.

## Tecnologias Utilizadas

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