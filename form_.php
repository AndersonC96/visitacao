<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
        header("Location: index.php");
        exit();
    }
    include 'db.php';
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
        header("Location: index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Simple Pharma | Painel de Controle</title>
        <link rel="icon" href="https://static.wixstatic.com/media/5ede7b_719545c97a084f288b8566db52756425%7Emv2.png/v1/fill/w_180%2Ch_180%2Clg_1%2Cusm_0.66_1.00_0.01/5ede7b_719545c97a084f288b8566db52756425%7Emv2.png" type="image/x-icon" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link rel="stylesheet" href="./CSS/navbar.css">
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
                            <a class="nav-link" href="dashboard.php"><i class="fas fa-home"></i> Voltar ao Início</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
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
                                        <input class="form-check-input" type="checkbox" id="ciclo1" name="ciclo[]" value="Resvitech">
                                        <label class="form-check-label" for="ciclo1">Resvitech</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="ciclo2" name="ciclo[]" value="Pepstrong">
                                        <label class="form-check-label" for="ciclo2">Pepstrong</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="ciclo3" name="ciclo[]" value="Vinogrape">
                                        <label class="form-check-label" for="ciclo3">Vinogrape</label>
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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
    </body>
</html>