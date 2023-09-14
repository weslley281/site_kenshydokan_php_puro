<?php 
include_once("classes/conexao.php");
    $c = new conectar();
    $conexao = $c->conexao();
 ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Registre-se</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-7">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Crie uma Conta</h3></div>
                                    <div class="card-body">
                                        <form action="funcoes/registrar_usuario.php" method="post">
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="inputFirstName">Primeiro Nome</label>
                                                        <input name="nome1" class="form-control py-4" id="inputFirstName" type="text" placeholder="Escreva primeiro nome" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="inputLastName">Segundo Nome</label>
                                                        <input name="nome2" class="form-control py-4" id="inputLastName" type="text" placeholder="Escreva ultimo nome" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="small mb-1" for="inputEmailAddress">Email</label>
                                                <input name="email" class="form-control py-4" id="inputEmailAddress" type="email" aria-describedby="emailHelp" placeholder="Escreva seu melhor Email" />
                                            </div>

                                            <div class="form-group">
                                                <label class="small mb-1" for="telefone">Telefone</label>
                                                <input name="telefone" class="form-control py-4" id="telefone" type="text" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);">
                                            </div>

                                            <div class="form-group">
                                                <label class="small mb-1" for="tipo">Tipo de Usuario</label>
                                                <select name="tipo" id="tipo" class="form-control" >
                                                    <option value="1">Diretor</option>
                                                    <option value="2">Professor</option>
                                                    <option value="3">Aluno</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label class="small mb-1" for="tipo">Qual Filiado</label>
                                                <select name="id_filiado" id="tipo" class="form-control" >
                                                    <?php 
                                                        $busca = "SELECT * FROM filiados order by nome asc";
                                                        $resultado = mysqli_query($conexao, $busca);
                                                        $linha = mysqli_num_rows($resultado);
                                                        if($linha == ''){
                                                            echo "<h3> Não foram encontrados dados Cadastrados no Banco!! </h3>";
                                                        }else{
                                                             while($res = mysqli_fetch_array($resultado)){
                                                            $id_filiado = $res["id_filiado"];
                                                            $nome = $res["nome"];
                                                      ?>
                                                    <option value="<?php echo $id_filiado ?>"><?php echo $nome; ?></option>
                                                    <?php }} ?>
                                                </select>
                                            </div>

                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="inputPassword">Senha</label>
                                                        <input name="senha1" class="form-control py-4" id="inputPassword" type="password" placeholder="Escreva sua Senha" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="inputConfirmPassword">Repita a Senha</label>
                                                        <input name="senha2" class="form-control py-4" id="inputConfirmPassword" type="password" placeholder="Escreva sua Senha Novamente" />
                                                    </div>
                                                </div>
                                            </div>
                                            <input class="form-group mt-4 mb-0 btn btn-success btn-block" type="submit" name="" value="Criar Conta">
                                        </form>
                                    </div>
                                    <div class="card-footer text-center">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Weslley Henrique Vieira Ferraz 2020</div>
                            <div>
                                
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>

<script type="text/javascript">
    function mask(o, f) {
      setTimeout(function() {
        var v = mphone(o.value);
        if (v != o.value) {
          o.value = v;
        }
      }, 1);
    }

    function mphone(v) {
      var r = v.replace(/\D/g, "");
      r = r.replace(/^0/, "");
      if (r.length > 10) {
        r = r.replace(/^(\d\d)(\d{5})(\d{4}).*/, "($1) $2-$3");
      } else if (r.length > 5) {
        r = r.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "($1) $2-$3");
      } else if (r.length > 2) {
        r = r.replace(/^(\d\d)(\d{0,5})/, "($1) $2");
      } else {
        r = r.replace(/^(\d*)/, "($1");
      }
      return r;
    }
 </script>