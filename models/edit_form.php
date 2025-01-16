<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
        header("Location: ./index.php");
        exit();
    }
    require '../config/db.php';
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
<?php
    $title = "Visualizar formulários";
    include '../views/templates/header.php';
    include '../views/templates/navbar.php';
?>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){
        $('#telefone').mask('(00) 0000-0000');
        $('#celular').mask('(00) 00000-0000');
    });
</script>
<?php
        }else{
            echo "Registro não encontrado.";
        }
    }else{
        echo "ID inválido.";
    }
?>
<?php include '../views/templates/footer.php'; // Inclui o rodapé ?>