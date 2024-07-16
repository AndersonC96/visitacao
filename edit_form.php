<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
        header("Location: index.php");
        exit();
    }
    require 'db.php';
    if(isset($_GET['id']) && is_numeric($_GET['id'])){
        $id = $_GET['id'];
        $sql = "SELECT * FROM forms WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if($row){
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Simple Pharma | Editar Cadastro</title>
        <link rel="icon" href="https://static.wixstatic.com/media/5ede7b_719545c97a084f288b8566db52756425%7Emv2.png/v1/fill/w_180%2Ch_180%2Clg_1%2Cusm_0.66_1.00_0.01/5ede7b_719545c97a084f288b8566db52756425%7Emv2.png" type="image/x-icon" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link rel="stylesheet" href="./CSS/navbar.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
        <style>
            .form-control{
                margin-bottom: 10px;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-custom shadow-sm bg-light">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="https://static.wixstatic.com/media/fef91e_c3f644e14da442178f706149ae38d838~mv2.png/v1/crop/x_0,y_24,w_436,h_262/fill/w_120,h_71,al_c,q_85,usm_0.66_1.00_0.01,enc_auto/CAPA-03.png" alt="Logo" style="height: 50px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="view_forms.php"><i class="fas fa-eye"></i> Voltar aos Cadastros</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container mt-5">
            <h2>Editar Cadastro</h2>
            <form action="update_form.php" method="post">
                <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                <b>Nome</b><input type="text" name="nome" value="<?= htmlspecialchars($row['nome']) ?>" class="form-control"><br>
                <b>Telefone</b><input type="text" name="telefone" value="<?= htmlspecialchars($row['telefone']) ?>" class="form-control" id="telefone"><br>
                <b>Celular</b><input type="text" name="celular" value="<?= htmlspecialchars($row['celular']) ?>" class="form-control" id="celular"><br>
                <b>Email</b><input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" class="form-control" pattern="[^ @]*@[^ @]*\.(com|com\.br)$" required><br>
                <b>Profissão</b><input type="text" name="profissao" value="<?= htmlspecialchars($row['profissao']) ?>" class="form-control"><br>
                <b>Número de Registro</b><input type="text" name="numero_registro" value="<?= htmlspecialchars($row['numero_registro']) ?>" class="form-control"><br>
                <b>Cidade</b><input type="text" name="cidade" value="<?= htmlspecialchars($row['cidade']) ?>" class="form-control"><br>
                <b>Estado</b><select name="estado" class="form-control">
                <option value="">Selecione o Estado</option>
                <option value="SP">São Paulo</option>
                <option value="AC">Acre</option>
                <option value="AL">Alagoas</option>
                <option value="AP">Amapá</option>
                <option value="AM">Amazonas</option>
                <option value="BA">Bahia</option>
                <option value="CE">Ceará</option>
                <option value="DF">Distrito Federal</option>
                <option value="ES">Espírito Santo</option>
                <option value="GO">Goiás</option>
                <option value="MA">Maranhão</option>
                <option value="MT">Mato Grosso</option>
                <option value="MS">Mato Grosso do Sul</option>
                <option value="MG">Minas Gerais</option>
                <option value="PA">Pará</option>
                <option value="PB">Paraíba</option>
                <option value="PR">Paraná</option>
                <option value="PE">Pernambuco</option>
                <option value="PI">Piauí</option>
                <option value="RJ">Rio de Janeiro</option>
                <option value="RN">Rio Grande do Norte</option>
                <option value="RS">Rio Grande do Sul</option>
                <option value="RO">Rondônia</option>
                <option value="RR">Roraima</option>
                <option value="SC">Santa Catarina</option>
                <option value="SE">Sergipe</option>
                <option value="TO">Tocantins</option>
                </select><br>
                <input type="hidden" name="data_hora" value="<?= date('Y-m-d H:i:s') ?>">
                <input type="submit" value="Atualizar" class="btn btn-primary">
            </form>
        </div>
        <script>
            $(document).ready(function(){
                $('#telefone').mask('(00) 0000-0000');
                $('#celular').mask('(00) 00000-0000');
            });
        </script>
    </body>
</html>
<?php
        }else{
            echo "Registro não encontrado.";
        }
    }else{
        echo "ID inválido.";
    }
?>