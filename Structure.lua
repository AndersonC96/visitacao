visitacao/
│
├── assets/                     # Recursos estáticos
│   ├── css/                    # Arquivos CSS
│   │   └── style.css           # Estilo global do projeto
│   ├── js/                     # Scripts JavaScript (se necessário)
│   ├── images/                 # Imagens do projeto
│
├── config/                     # Arquivos de configuração
│   ├── db.php                  # Configuração da conexão com o banco de dados
│   └── config.php              # Configurações gerais do projeto
│
├── controllers/                # Arquivos responsáveis pela lógica do sistema
│   ├── UserController.php      # Lógica para gerenciar usuários
│   ├── FormController.php      # Lógica para gerenciar formulários
│   └── ExportController.php    # Lógica para exportação de dados
│
├── models/                     # Classes para interagir com o banco de dados
│   ├── User.php                # Modelo para usuários
│   ├── Form.php                # Modelo para formulários
│
├── views/                      # Arquivos responsáveis pela interface
│   ├── templates/              # Templates reutilizáveis (e.g., cabeçalho, rodapé)
│   │   ├── header.php          # Cabeçalho do site
│   │   ├── footer.php          # Rodapé do site
│   ├── users/                  # Páginas relacionadas aos usuários
│   │   ├── list.php            # Listagem de usuários
│   │   ├── create.php          # Formulário de criação de usuário
│   │   └── edit.php            # Formulário de edição de usuário
│   ├── forms/                  # Páginas relacionadas aos formulários
│       ├── list.php            # Listagem de formulários
│       └── edit.php            # Edição de formulários
│
├── public/                     # Pasta acessível ao público
│   ├── index.php               # Página inicial
│   ├── login.php               # Página de login
│   ├── logout.php              # Script de logout
│   └── .htaccess               # Configuração para segurança e URLs amigáveis
│
├── logs/                       # Logs do sistema (não versionados)
│   └── error_log               # Logs de erro
│
├── tests/                      # Testes do sistema
│   ├── UserTest.php            # Testes relacionados aos usuários
│   ├── FormTest.php            # Testes relacionados aos formulários
│
├── .gitignore                  # Arquivos e pastas ignorados pelo Git
├── README.md                   # Documentação do projeto
├── composer.json               # Dependências do Composer
├── composer.lock               # Arquivo gerado pelo Composer
└── index.php                   # Ponto de entrada principal