<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
        header("Location: ./index.php");
        exit();
    }
    include '../config/db.php';
    date_default_timezone_set('America/Sao_Paulo');
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT nome, sobrenome FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0){
        $user = $result->fetch_assoc();
        $nome_do_usuario = $user['nome'];
        $sobrenome_do_usuario = $user['sobrenome'];
    }else{
        header("Location: ./index.php");
        exit();
    }
?>
<?php
    $title = "Formulário"; // Define o título da página
    include '../views/templates/header.php'; // Inclui o cabeçalho
    include '../views/templates/navbar.php'; // Inclui a Navbar
?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-center">Preencha o Formulário</h2>
                </div>
                <div class="card-body">
                    <form method="post" action="process_form.php">
                        <div class="mb-3">
                            <label for="nome" class="form-label"><b>Nome</b></label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="numero_registro" class="form-label"><b>Número de Conselho</b></label>
                            <input type="text" class="form-control" id="numero_registro" name="numero_registro" required>
                        </div>
                        <div class="mb-3">
                            <label for="nome_conselho" class="form-label"><b>Conselho</b></label>
                            <input type="text" class="form-control" id="nome_conselho" name="nome_conselho" required>
                        </div>
                        <div class="mb-3">
                            <label for="profissao" class="form-label"><b>Especialidade</b></label>
                            <select class="form-select" id="profissao" name="profissao" required>
                                <option value="">Selecione a Especialidade</option>
                                <option value="Médico">Médico (a)</option>
                                <option value="Nutricionista">Nutricionista</option>
                                <option value="Biomédico">Biomédico</option>
                                <option value="Farmacêutico">Farmacêutico (a)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="data_hora" class="form-label"><b>Data e Hora</b></label>
                            <input type="datetime-local" class="form-control" id="data_hora" name="data_hora" value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="endereco" class="form-label"><b>Endereço</b></label>
                            <input type="text" class="form-control" id="endereco" name="endereco" required>
                        </div>
                        <div class="mb-3">
                            <label for="cidade" class="form-label"><b>Cidade</b></label>
                            <input type="text" class="form-control" id="cidade" name="cidade" required>
                        </div>
                        <div class="mb-3">
                            <label for="estado" class="form-label"><b>Estado</b></label>
                            <select class="form-select" id="estado" name="estado" required>
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
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="visita" class="form-label"><b>Tipo da visita</b></label>
                            <select class="form-select" id="visita" name="visita" required>
                                <option value="">Selecione a Opção</option>
                                <option value="Presencial">Presencial</option>
                                <option value="Remota">Remota</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><b>Brand do Ciclo</b></label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="ciclo1" name="ciclo[]" value="Clonapure">
                                <label class="form-check-label" for="ciclo1">Clonapure</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="ciclo2" name="ciclo[]" value="Odilia">
                                <label class="form-check-label" for="ciclo2">Odilia</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="ciclo3" name="ciclo[]" value="Bioberon">
                                <label class="form-check-label" for="ciclo3">Bioberon</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="observacao" class="form-label"><b>Observações</b></label>
                            <textarea class="form-control" id="observacao" name="observacao" rows="3"></textarea>
                        </div>
                        <input type="hidden" id="user_id" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                        <input type="hidden" id="representante" name="representante" value="<?php echo htmlspecialchars($nome_do_usuario) . ' ' . htmlspecialchars($sobrenome_do_usuario); ?>">
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){
        $('#telefone').mask('(00) 0000-0000');
        $('#celular').mask('(00) 00000-0000');
        $('#nome').on('blur', function(){
            var nome = $(this).val();
            if(nome){
                $.ajax({
                    url: 'get_user_info.php',
                    type: 'GET',
                    data: { nome: nome },
                    success: function(data){
                        var userInfo = JSON.parse(data);
                        if(userInfo){
                            $('#numero_registro').val(userInfo.numero_registro);
                            $('#nome_conselho').val(userInfo.nome_conselho);
                            $('#profissao').val(userInfo.profissao);
                            $('#endereco').val(userInfo.endereco);
                            $('#cidade').val(userInfo.cidade);
                            $('#estado').val(userInfo.estado);
                        }
                    }
                });
            }
        });
        function getQueryParams(){
            var params = {};
            window.location.search.substring(1).split("&").forEach(function(pair){
                pair = pair.split("=");
                params[pair[0]] = decodeURIComponent(pair[1] || "");
            });
            return params;
        }
        $(document).ready(function(){
            var params = getQueryParams();
            if(params.success === "true"){
                alert("Formulário enviado com sucesso!");
            }else if(params.success === "false"){
                alert("Ocorreu um erro ao enviar o formulário: " + params.error);
            }
        });
    });
</script>
<?php include '../views/templates/footer.php'; // Inclui o rodapé ?>