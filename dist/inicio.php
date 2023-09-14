<?php
    include_once("menu.php");
    $usuario = $_SESSION['usuario'];
    if(isset($_SESSION['usuario'])){
        include_once("classes/conexao.php");
        $c = new conectar();
        $conexao = $c->conexao();
?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Area Administrativa</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Area Administrativa</li>
                        </ol>
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">Novos Pedidos</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">Mais Detalhes</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body">Pedidos em Andamento</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">Mais Detalhes</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">Pedidos Concluidos</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">Mais Detalhes</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">Pedidos Cancelados</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">Mais Detalhes</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php 
                        if (isset($_GET["filiados"])) { ?>
                        <!-- Listar Filiados -->
                        <div class="card mb-4">
                            <button type="button" class="btn btn-success mt-3 mb-3 mr-3 ml-3 col-lg-3" data-toggle="modal" data-target="#ModalFiliado">
                              Cadastrar Filiado
                            </button>
                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                                Lista de Filiados
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Numero</th>
                                                <th>Nome</th>
                                                <th>Graduação</th>
                                                <th>Telefone</th>
                                                <th>Email</th>
                                                <th>Ativo</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php 
                                            $busca_filiados = "SELECT * FROM filiados";
                                            $resultado_filiados = mysqli_query($conexao, $busca_filiados);
                                            $linha_filiados = mysqli_num_rows($resultado_filiados);
                                            if($linha_filiados == ''){
                                                echo "<h3> Não foram encontrados dados Cadastrados no Banco!! </h3>";
                                            }else{
                                                while($res = mysqli_fetch_array($resultado_filiados)){
                                                    $id_filiado = $res["id_filiado"];
                                                    $id_graduacao = $res["id_graduacao"];
                                                    $nome = $res["nome"];
                                                    $dojo = $res["dojo"];
                                                    $telefone = $res["telefone"];
                                                    $rg = $res["rg"];
                                                    $email = $res["email"];
                                                    $confirmacao = $res["confirmacao"];

                                                    $busca_graduacao = "SELECT * FROM graduacao WHERE id_graduacao = '$id_graduacao'";
                                                    $resultado_graduacao = mysqli_query($conexao, $busca_graduacao);
                                                    $graduacao = mysqli_fetch_array($resultado_graduacao);
                                             ?>
                                            <tr>
                                                <td><?php echo $id_filiado; ?></td>
                                                <td><?php echo $nome; ?></td>
                                                <td><?php echo $graduacao["graduacao"]; ?></td>
                                                <td><?php echo $telefone; ?></td>
                                                <td><?php echo $email; ?></td>
                                                <td><?php echo $confirmacao; ?></td>
                                                <td>
                                                    <a href="" class="btn btn-info" data-toggle="modal" data-target="#ModalEditarFiliado<?php echo $id_filiado ?>"><i class="fas fa-edit"></i></a>

                                                    <a title="Excluir" class="btn btn-danger" href="funcoes/deletar_filiado.php?id=<?php echo $id_filiado; ?>"><i class="fa fa-minus-square"></i></a>
                                                </td>
                                            </tr>
                                            <?php }} ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <?php }elseif (isset($_GET["postagens"])) { ?>
                        <!-- Listar Postagens -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                                Lista de Postagens
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Titulo</th>
                                                <th>Autor</th>
                                                <th>Ativo</th>
                                                <th>Data</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php 
                                            $busca = "SELECT * FROM postagens";
                                            $resultado = mysqli_query($conexao, $busca);
                                            $linha = mysqli_num_rows($resultado);
                                            if($linha == ''){
                                                        echo "<h3> Não foram encontrados dados Cadastrados no Banco!! </h3>";
                                                    }else{
                                                        while($res = mysqli_fetch_array($resultado)){
                                                            $id_postagem = $res["id_postagem"];
                                                            $id_usuario = $res["id_usuario"];
                                                            $titulo = $res["titulo"];
                                                            $situacao = $res["situacao"];
                                                            $data = $res["data"];

                                                            //busca dados do autor
                                                            $consulta = "SELECT * FROM usuarios WHERE id_usuario = '$id_usuario'";
                                                            $resultado2 = mysqli_query($conexao, $consulta);
                                                            $dado = mysqli_fetch_array($resultado2)
                                             ?>
                                            <tr>
                                                <td><?php echo $titulo; ?></td>
                                                <td><?php echo $dado["nome"]; ?></td>
                                                <td><?php echo $situacao; ?></td>
                                                <td><?php echo $data; ?></td>
                                                <td>
                                                    <a href="" class="btn btn-info" data-toggle="modal" data-target="#ModalEditarPostagem<?php echo $id_postagem ?>"><i class="fas fa-edit"></i></a>

                                                    <a title="Excluir" class="btn btn-danger" href="../funcoes/deletar_postagem.php?id=<?php echo $id_postagem; ?>"><i class="fa fa-minus-square"></i></a>
                                                </td>
                                            </tr>
                                            <?php }} ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    <?php }elseif (isset($_GET["galerias"])) { ?>
                        <!-- Listar Postagens -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                                Lista de Galerias
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Titulo</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php 
                                            $busca_galeria = "SELECT * FROM galeria";
                                            $resultado_galeria = mysqli_query($conexao, $busca_galeria);
                                            $linha_galeria = mysqli_num_rows($resultado_galeria);
                                            if($linha_galeria == ''){
                                                        echo "<h3> Não foram encontrados dados Cadastrados no Banco!! </h3>";
                                            }else{
                                                while($galeria = mysqli_fetch_array($resultado_galeria)){
                                             ?>
                                            <tr>
                                                <td><?php echo $galeria["id_galeria"]; ?></td>
                                                <td><?php echo $galeria["nome"]; ?></td>
                                                <td>
                                                    <a href="editar_galeria.php?galeria=<?php echo $galeria["id_galeria"] ?>" class="btn btn-info"><i class="fas fa-edit"></i></a>

                                                    <a title="Excluir" class="btn btn-danger" href="funcoes/deletar_galeria.php?galeria=<?php echo $galeria["id_galeria"]; ?>"><i class="fa fa-minus-square"></i></a>
                                                </td>
                                            </tr>
                                            <?php }} ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <?php }elseif (isset($_GET["graduacao"])) { ?>
                        <!-- Listar Graduações -->
                        <div class="card mb-4">
                            <!-- Botão para acionar modal -->
                            <button type="button" class="btn btn-success mt-3 mb-3 mr-3 ml-3 col-lg-3" data-toggle="modal" data-target="#ModalGraduacoes">
                              Cadastrar Graduações
                            </button>

                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                                Lista de Graduações
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Graduação</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php 
                                            $busca_graduacao = "SELECT * FROM graduacao";
                                            $resultado_graduacao = mysqli_query($conexao, $busca_graduacao);
                                            $linha_graduacao = mysqli_num_rows($resultado_graduacao);
                                            if($linha_graduacao == ''){
                                                echo "<h3> Não foram encontrados dados Cadastrados no Banco!! </h3>";
                                            }else{
                                                while($res = mysqli_fetch_array($resultado_graduacao)){
                                             ?>
                                            <tr>
                                                <td><?php echo $res["id_graduacao"]; ?></td>
                                                <td><?php echo $res["graduacao"]; ?></td>
                                                <td>
                                                    <a href="" class="btn btn-info" data-toggle="modal" data-target="#ModalEditarGraduacoes<?php echo $res["id_graduacao"] ?>"><i class="fas fa-edit"></i></a>

                                                    <a title="Excluir" class="btn btn-danger" href="funcoes/deletar_graduacao.php?id=<?php echo $res["id_graduacao"]; ?>"><i class="fa fa-minus-square"></i></a>
                                                </td>
                                            </tr>
                                            <?php }} ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    <?php }elseif (isset($_GET["categorias"])) { ?>
                        <!-- Listar Graduações -->
                        <div class="card mb-4">
                            <!-- Botão para acionar modal -->
                            <button type="button" class="btn btn-success mt-3 mb-3 mr-3 ml-3 col-lg-3" data-toggle="modal" data-target="#ModalCategorias">
                              Cadastrar Categorias
                            </button>

                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                                Lista de Categorias
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Categoria</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php 
                                            $busca_graduacao = "SELECT * FROM categorias";
                                            $resultado_graduacao = mysqli_query($conexao, $busca_graduacao);
                                            $linha_graduacao = mysqli_num_rows($resultado_graduacao);
                                            if($linha_graduacao == ''){
                                                echo "<th><h3> Não foram encontrados dados Cadastrados no Banco!! </h3></th>";
                                            }else{
                                                while($res = mysqli_fetch_array($resultado_graduacao)){
                                             ?>
                                            <tr>
                                                <td><?php echo $res["id_categoria"]; ?></td>
                                                <td><?php echo $res["categoria"]; ?></td>
                                                <td>
                                                    <a href="" class="btn btn-info" data-toggle="modal" data-target="#ModalEditarCategoria<?php echo $res["id_categoria"] ?>"><i class="fas fa-edit"></i></a>

                                                    <a title="Excluir" class="btn btn-danger" href="funcoes/deletar_categoria.php?id=<?php echo $res["id_categoria"]; ?>"><i class="fa fa-minus-square"></i></a>
                                                </td>
                                            </tr>
                                            <?php }} ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <?php }elseif (isset($_GET["cursos"])) { ?>
                        <!-- Listar Cursos -->
                        <div class="card mb-4">
                            <button type="button" class="btn btn-success mt-3 mb-3 mr-3 ml-3 col-lg-3" data-toggle="modal" data-target="#ModalCursos">
                              Cadastrar Cursos
                            </button>
                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                                Lista de Cursos
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Professor</th>
                                                <th>Categoria</th>
                                                <th>Data</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php 
                                            $busca = "SELECT * FROM curso";
                                            $resultado = mysqli_query($conexao, $busca);
                                            $linha = mysqli_num_rows($resultado);
                                            if($linha == ''){
                                                echo "<th><h3> Não foram encontrados dados Cadastrados no Banco!! </h3></th>";
                                            }else{
                                                while($res = mysqli_fetch_array($resultado)){
                                                    $id_curso = $res["id_curso"];
                                                    $nome = $res["nome"];
                                                    $professor = $res["professor"];
                                                    $id_categoria = $res["id_categoria"];
                                                    $data = $res["data"];

                                                    $busca2 = "SELECT * FROM categorias WHERE id_categoria = '$id_categoria'";
                                                    $resultado2 = mysqli_query($conexao, $busca2);
                                                    $dado = mysqli_fetch_array($resultado2);
                                             ?>
                                            <tr>
                                                <td><?php echo $nome; ?></td>
                                                <td><?php echo $professor; ?></td>
                                                <td><?php echo $dado["categoria"]; ?></td>
                                                <td><?php echo $data; ?></td>
                                                <td>
                                                    <a href="" class="btn btn-success mb-1" data-toggle="modal" data-target="#ModalAdcionaAula<?php echo $id_curso ?>" title="Adicionar Aulas"><i class="fas fa-plus"></i></a>

                                                    <a href="" class="btn btn-info mb-1" data-toggle="modal" data-target="#ModalEditarCurso<?php echo $id_curso ?>" title="Editar Curso"><i class="fas fa-edit"></i></a>

                                                    <a title="Excluir" class="btn btn-danger mb-1" href="funcoes/deletar_curso.php?id=<?php echo $id_curso; ?>&id_img=<?php echo $res['id_imagem'] ?>" title="Excluir curso"><i class="fa fa-minus-square"></i></a>
                                                </td>
                                            </tr>
                                            <?php }} ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </main>
            
<?php 
    include_once("rodape.php");
}else{
    echo "<script language='javascript'>window.alert('ERRO - não está Logado'); </script>";
    echo "<script language='javascript'>window.location='login.php'; </script>";
}
?>

<!-- Modais de Cadastro -->



<!-- Modal Cadastra Categorias-->
<div class="modal fade" id="ModalCategorias" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="TituloModalCentralizado">Cadastrar Categorias</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="funcoes/cadastrar_categorias.php" method="post">
            <input class="form-control" type="text" placeholder="Nome da Categoria" name="categoria">
            <input class="form-control" type="hidden" name="id_adm" value="<?php echo $id_adm ?>">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-success">Salvar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Cadastra Cursos-->
<div class="modal fade" id="ModalCursos" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="TituloModalCentralizado">Cadastrar Cursos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="funcoes/cadastrar_curso.php" method="post" enctype="multipart/form-data">
            <input class="form-control mb-3" type="text" placeholder="Nome do Curso" name="nome">
            <select class="form-control mb-3" name="id_categoria">
                <?php 
                $busca_graduacao = "SELECT * FROM categorias";
                $resultado_graduacao = mysqli_query($conexao, $busca_graduacao);
                while($res = mysqli_fetch_array($resultado_graduacao)){
                 ?>
                <option value="<?php echo $res["id_categoria"] ?>"><?php echo $res["categoria"]; ?></option>
                <?php } ?>
            </select>
            <input class="form-control mb-3" type="text" placeholder="Descrição" name="descricao">
            <input class="form-control mb-3" type="text" placeholder="Nome do Professor" name="professor">
            <input name="foto" type="file" class="form-control mb-3" id="foto" v-model="imagem">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-success">Salvar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Cadastra Filiados-->
<div class="modal fade" id="ModalFiliado" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="TituloModalCentralizado">Cadastrar Graduações</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="funcoes/cadastrar_filiado.php" method="post">
            <div class="form-group">
            <label for="nome">Nome</label>
            <input name="nome" type="text" class="form-control" id="nome">
            </div>

            <div class="form-group">
            <label for="dojo">Dojo</label>
            <input name="dojo" type="text" class="form-control" id="dojo">
            </div>

            <div class="form-group">
            <label for="estado">Graduação</label>
            <select name="id_graduacao" class="form-control" id="graduacao">
                <?php 
                $busca = "SELECT * FROM graduacao";
                $resultado = mysqli_query($conexao, $busca);
                while ($res = mysqli_fetch_array($resultado)) {
                    ?>
                <option value="<?php echo $res['id_graduacao'] ?>"><?php echo $res["graduacao"]; ?></option>
                <?php } ?>
            </select>
            </div>

            <div class="form-group">
            <label for="telefone">Telefone</label>
            <input name="telefone" type="text" class="form-control" id="telefone">
            </div>

            <div class="form-group">
            <label for="rg">RG</label>
            <input name="rg" type="text" class="form-control" id="rg">
            </div>

            <div class="form-group">
            <label for="email">Email</label>
            <input name="email" type="email" class="form-control" id="email">
            </div>

            <div class="form-group">
            <label for="endereco">Endereço</label>
            <input name="endereco" type="text" class="form-control" id="endereco">
            </div>

            <div class="form-group">
            <label for="cidade">Cidade</label>
            <input name="cidade" type="text" class="form-control" id="cidade">
            </div>

            <div class="form-group">
            <label for="estado">Estado</label>
            <select name="id_estado" class="form-control" id="graduacao">
                <?php 
                $busca = "SELECT * FROM estados";
                $resultado = mysqli_query($conexao, $busca);
                while ($res = mysqli_fetch_array($resultado)) {
                    ?>
                <option value="<?php echo $res['id_estado'] ?>"><?php echo $res["estado"]; ?></option>
                <?php } ?>
            </select>
            </div>

            <div class="form-group">
            <label for="estado">Confirmação</label>
            <select name="confirmacao" class="form-control" id="graduacao">
                <option>sim</option>
                <option>nao</option>
            </select>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-success">Salvar</button>
        </form>
      </div>
    </div>
  </div>
</div>






<!-- Modais de Edição -->

<!-- Modal Edita graduações -->
<?php 
if(isset($_GET["graduacao"])) {
$busca_graduacao = "SELECT * FROM graduacao";
$resultado_graduacao = mysqli_query($conexao, $busca_graduacao);
while($res = mysqli_fetch_array($resultado_graduacao)){ 
?>
<div class="modal fade" id="ModalEditarGraduacoes<?php echo $res["id_graduacao"] ?>" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="TituloModalCentralizado">Editar Graduações</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="funcoes/editar_graduacao.php" method="post">
            <input type="hidden" value="<?php echo $res["id_graduacao"] ?>" name="id_graduacao">
            <input class="form-control" type="text" value="<?php echo $res["graduacao"] ?>" name="graduacao">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary">Salvar mudanças</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php }} ?>

<!-- Modal Edita categorias -->
<?php 
if(isset($_GET["categorias"])) {
$busca_categoria = "SELECT * FROM categorias";
$resultado_categoria = mysqli_query($conexao, $busca_categoria);
while($res = mysqli_fetch_array($resultado_categoria)){ 
?>
<div class="modal fade" id="ModalEditarCategoria<?php echo $res["id_categoria"] ?>" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="TituloModalCentralizado">Editar Categoria</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="funcoes/editar_categoria.php" method="post">
            <input type="hidden" value="<?php echo $res["id_categoria"] ?>" name="id_categoria">
            <input class="form-control" type="text" value="<?php echo $res["categoria"] ?>" name="categoria">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary">Salvar mudanças</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php }} ?>

<!-- Modal Edita Postagens -->
<?php 
if(isset($_GET["postagens"])) {
$busca_postagens = "SELECT * FROM postagens";
$resultado_postagens = mysqli_query($conexao, $busca_postagens);
while($res = mysqli_fetch_array($resultado_postagens)){ 
?>
<div class="modal fade" id="ModalEditarPostagem<?php echo $res["id_postagem"] ?>" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="TituloModalCentralizado">Editar Postagens</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="funcoes/editar_situacao_postagem.php" method="post">
            <div class="container-fluid">
                <?php echo $res["conteudo"]; ?>
            </div>
            <input type="hidden" value="<?php echo $res["id_postagem"] ?>" name="id_postagem">
            <select class="form-control" name="situacao">
                <option><?php echo $situacao; ?></option>
                <?php if ($res["situacao"] != "sim") { ?>
                <option>sim</option>
                <?php }elseif ($res["situacao"] != "nao") { ?>
                <option>nao</option>
                <?php } ?>
            </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary">Salvar mudanças</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php }} ?>

<!-- Modal Edita Categoria -->
<?php 
if(isset($_GET["postagens"])) {
$busca_postagens = "SELECT * FROM postagens";
$resultado_postagens = mysqli_query($conexao, $busca_postagens);
while($res = mysqli_fetch_array($resultado_postagens)){ 
?>
<div class="modal fade" id="ModalEditarCategoria<?php echo $res["id_categoria"] ?>" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="TituloModalCentralizado">Editar Postagens</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        postagens
            <input type="hidden" value="<?php echo $res["id_categoria"] ?>" name="id_categoria">
            <input class="form-control" type="text" value="<?php echo $res["categoria"] ?>" name="categoria">
      </div>
      <div class="modal-footer">
        <form action="">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary">Salvar mudanças</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php }} ?>


<!-- Modal Edita curso -->
<?php 
if(isset($_GET["cursos"])) {
$busca_cursos = "SELECT * FROM curso";
$resultado_cursos = mysqli_query($conexao, $busca_cursos);
while($res = mysqli_fetch_array($resultado_cursos)){ 
    //busca imagem
    $id_imagem = $res["id_imagem"];
    $busca_imagem = "SELECT * FROM imagens WHERE id_imagem = '$id_imagem'";
    $resultado_imagem = mysqli_query($conexao, $busca_imagem);
    $img = mysqli_fetch_array($resultado_imagem);
    //busca categoria
    $id_categoria = $res["id_categoria"];
    $busca_categoria = "SELECT * FROM categorias WHERE id_categoria = '$id_categoria'";
    $resultado_categoria = mysqli_query($conexao, $busca_categoria);
    $cat = mysqli_fetch_array($resultado_categoria);


    if ($res["situacao"] == 1) {
        $processo = "Finalizado";
    }elseif ($res["situacao"] == 2) {
        $processo = "Em andamento";
    }
?>
<div class="modal fade" id="ModalEditarCurso<?php echo $res["id_curso"] ?>" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="TituloModalCentralizado">Editar Curso</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <form action="funcoes/editar_imagem_curso.php" method="post" enctype="multipart/form-data">
            <input type="hidden" value="<?php echo $res["id_curso"] ?>" name="id_curso">
            <input name="foto" type="file" class="form-control mb-3" id="foto" v-model="imagem">
            <button type="submit" class="btn-secondary mb-3">Enviar Imagem</button>
        </form>

        <center>
        <div class="card mb-3" style="width: 18rem;">
          <img class="card-img-top" src="../imagens_produtos/<?php echo $img['nome'] ?>" alt="Card image cap">
        </div>
        </center>

        <form action="funcoes/editar_curso.php" method="post">
            <input type="hidden" value="<?php echo $res["id_curso"] ?>" name="id_curso">
            <input class="form-control mb-3" type="text" value="<?php echo $res['nome']; ?>" name="nome">
            <select class="form-control mb-3" name="id_categoria">
                <option value="<?php echo $res['id_categoria'] ?>"><?php echo $cat["categoria"]; ?></option>
                <?php 
                $busca_categoria = "SELECT * FROM categorias";
                $resultado_categoria = mysqli_query($conexao, $busca_categoria);
                while($dado = mysqli_fetch_array($resultado_categoria)){
                 ?>
                <option value="<?php echo $dado["id_categoria"] ?>"><?php echo $dado["categoria"]; ?></option>
                <?php } ?>
            </select>
            <input class="form-control mb-3" type="text" value="<?php echo $res['descricao']; ?>" name="descricao">
            <input class="form-control mb-3" type="text" value="<?php echo $res['professor']; ?>" name="professor">
            <select class="form-control mb-3" name="situacao">
                <option value="<?php echo $res['situacao'] ?>"><?php echo $processo; ?></option>
                <?php if ($res['situacao'] != 1) { ?>
                <option value="1">Finalizado</option>
                <?php } ?>
                <?php if ($res['situacao'] != 2) { ?>
                <option value="2">Em andamento</option>
                <?php } ?>
            </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary">Salvar mudanças</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php }} ?>

<!-- Modal Adciona Aula -->
<?php 
if(isset($_GET["cursos"])) {
$busca_cursos = "SELECT * FROM curso";
$resultado_cursos = mysqli_query($conexao, $busca_cursos);
while($res = mysqli_fetch_array($resultado_cursos)){
$id_curso2 = $res["id_curso"]; 
?>
<div class="modal fade" id="ModalAdcionaAula<?php echo $res["id_curso"] ?>" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="TituloModalCentralizado">Adcionar Aulas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <center>
            <div class="card mb-3" style="width: 18rem;">
              <h2><?php echo $res["nome"]; ?></h2>
              <img class="card-img-top" src="../imagens_produtos/<?php echo $img['nome'] ?>" alt="Card image cap">
            </div>
        </center>

        <table class="table">
          <thead>
            <tr>
              <th scope="col">Titulo</th>
              <th scope="col">Link</th>
              <th scope="col">Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $busca_aula = "SELECT * FROM aulas WHERE id_curso = '$id_curso2'";
            $resultado_aula = mysqli_query($conexao, $busca_aula);
            $linha_aula = mysqli_num_rows($resultado_aula);
            if ($linha_aula == 0 or $linha_aula == null) {
                echo "<tr><td><b>Ainda sem Aulas</b></td></tr>";
            }else{
                while ($aula = mysqli_fetch_array($resultado_aula)) {
             ?>
            <tr>
              <td><?php echo $aula["titulo"]; ?></td>
              <td><a href="<?php echo $aula["link"]; ?>"><?php echo $aula["titulo"]; ?></td>
              <td>
                  <a title="Excluir" class="btn btn-danger mb-1" href="funcoes/deletar_aula.php?id=<?php echo $aula["id_aula"]; ?>" title="Excluir Aula"><i class="fas fa-times-circle"></i></a>
              </td>
            </tr>
            <?php }} ?>
          </tbody>
        </table>

        <form action="funcoes/adcionar_aulas.php" method="post">
            <input type="hidden" value="<?php echo $res["id_curso"] ?>" name="id_curso">
            <input class="form-control mb-3" type="text" placeholder="Titulo da Aula" name="titulo">
            <input class="form-control mb-3" type="text" placeholder="Link da Aula" name="link">
            <input class="form-control mb-3 btn btn-success" value="Adcionar Aula" type="submit" name="">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
<?php }} ?>

<!-- Modal Adcionar -->
<?php 
if(isset($_GET["cursos"])) {
$busca_cursos = "SELECT * FROM curso";
$resultado_cursos = mysqli_query($conexao, $busca_cursos);
while($res = mysqli_fetch_array($resultado_cursos)){ 
    //busca imagem
    $id_imagem = $res["id_imagem"];
    $busca_imagem = "SELECT * FROM imagens WHERE id_imagem = '$id_imagem'";
    $resultado_imagem = mysqli_query($conexao, $busca_imagem);
    $img = mysqli_fetch_array($resultado_imagem);
    //busca categoria
    $id_categoria = $res["id_categoria"];
    $busca_categoria = "SELECT * FROM categorias WHERE id_categoria = '$id_categoria'";
    $resultado_categoria = mysqli_query($conexao, $busca_categoria);
    $cat = mysqli_fetch_array($resultado_categoria);


    if ($res["situacao"] == 1) {
        $processo = "Finalizado";
    }elseif ($res["situacao"] == 2) {
        $processo = "Em andamento";
    }
?>
<div class="modal fade" id="ModalEditarCurso<?php echo $res["id_curso"] ?>" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="TituloModalCentralizado">Editar Curso</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <form action="funcoes/editar_imagem_curso.php" method="post" enctype="multipart/form-data">
            <input type="hidden" value="<?php echo $res["id_curso"] ?>" name="id_curso">
            <input name="foto" type="file" class="form-control mb-3" id="foto" v-model="imagem">
            <button type="submit" class="btn-secondary mb-3">Enviar Imagem</button>
        </form>

        <center>
        <div class="card mb-3" style="width: 18rem;">
          <img class="card-img-top" src="../imagens_produtos/<?php echo $img['nome'] ?>" alt="Card image cap">
        </div>
        </center>

        <form action="funcoes/editar_curso.php" method="post">
            <input type="hidden" value="<?php echo $res["id_curso"] ?>" name="id_curso">
            <input class="form-control mb-3" type="text" value="<?php echo $res['nome']; ?>" name="nome">
            <select class="form-control mb-3" name="id_categoria">
                <option value="<?php echo $res['id_categoria'] ?>"><?php echo $cat["categoria"]; ?></option>
                <?php 
                $busca_categoria = "SELECT * FROM categorias";
                $resultado_categoria = mysqli_query($conexao, $busca_categoria);
                while($dado = mysqli_fetch_array($resultado_categoria)){
                 ?>
                <option value="<?php echo $dado["id_categoria"] ?>"><?php echo $dado["categoria"]; ?></option>
                <?php } ?>
            </select>
            <input class="form-control mb-3" type="text" value="<?php echo $res['descricao']; ?>" name="descricao">
            <input class="form-control mb-3" type="text" value="<?php echo $res['professor']; ?>" name="professor">
            <select class="form-control mb-3" name="situacao">
                <option value="<?php echo $res['situacao'] ?>"><?php echo $processo; ?></option>
                <?php if ($res['situacao'] != 1) { ?>
                <option value="1">Finalizado</option>
                <?php } ?>
                <?php if ($res['situacao'] != 2) { ?>
                <option value="2">Em andamento</option>
                <?php } ?>
            </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary">Salvar mudanças</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php }} ?>

<!-- Modal Edita Filiados -->
<?php 
if(isset($_GET["filiados"])) {
$busca_filiados = "SELECT * FROM filiados";
$resultado_filiados = mysqli_query($conexao, $busca_filiados);
while($dado = mysqli_fetch_array($resultado_filiados)){
    //busca dados da graduação
    $id_graduacao = $dado["id_graduacao"];
    $busca_graduacao = "SELECT * FROM graduacao WHERE id_graduacao = '$id_graduacao'";
    $resultado_graduacao = mysqli_query($conexao, $busca_graduacao);
    $dado_graduacao = mysqli_fetch_array($resultado_graduacao);
    //busca dados do Estados
    $id_estado = $dado["id_estado"];
    $busca_estado = "SELECT * FROM estados WHERE id_estado = '$id_estado'";
    $resultado_estado = mysqli_query($conexao, $busca_estado);
    $dado_estado = mysqli_fetch_array($resultado_estado);
?>
<div class="modal fade" id="ModalEditarFiliado<?php echo $dado["id_filiado"] ?>" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="TituloModalCentralizado">Editar Filiado</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="funcoes/editar_filiado.php" method="post">
            <input type="hidden" value="<?php echo $dado["id_filiado"] ?>" name="id_filiado">

            <div class="form-group">
            <label for="nome">Nome</label>
            <input name="nome" type="text" class="form-control" id="nome" value="<?php echo $dado['nome']; ?>">
            </div>

            <div class="form-group">
            <label for="dojo">Dojo</label>
            <input value="<?php echo $dado['dojo']; ?>" name="dojo" type="text" class="form-control" id="dojo">
            </div>[
            ]

            <div class="form-group">
            <label for="estado">Graduação</label>
            <select name="id_graduacao" class="form-control" id="graduacao">
                <option value="<?php echo $dado_graduacao['id_graduacao'] ?>"><?php echo $dado_graduacao["graduacao"]; ?></option>
                <?php 
                $busca = "SELECT * FROM graduacao";
                $resultado = mysqli_query($conexao, $busca);
                while ($res = mysqli_fetch_array($resultado)) {
                    ?>
                <option value="<?php echo $res['id_graduacao'] ?>"><?php echo $res["graduacao"]; ?></option>
                <?php } ?>
            </select>
            </div>

            <div class="form-group">
            <label for="telefone">Telefone</label>
            <input value="<?php echo $dado['telefone']; ?>" name="telefone" type="text" class="form-control" id="telefone">
            </div>

            <div class="form-group">
            <label for="rg">RG</label>
            <input value="<?php echo $dado['rg']; ?>" name="rg" type="text" class="form-control" id="rg">
            </div>

            <div class="form-group">
            <label for="email">Email</label>
            <input value="<?php echo $dado['email']; ?>" name="email" type="email" class="form-control" id="email">
            </div>

            <div class="form-group">
            <label for="endereco">Endereço</label>
            <input value="<?php echo $dado['endereco']; ?>" name="endereco" type="text" class="form-control" id="endereco">
            </div>

            <div class="form-group">
            <label for="cidade">Cidade</label>
            <input value="<?php echo $dado['cidade']; ?>" name="cidade" type="text" class="form-control" id="cidade">
            </div>

            <div class="form-group">
            <label for="estado">Estado</label>
            <select name="id_estado" class="form-control" id="graduacao">
                <option value="<?php echo $dado_estado["id_estado"]; ?>"><?php echo $dado_estado["estado"]; ?></option>
                <?php 
                $busca = "SELECT * FROM estados";
                $resultado = mysqli_query($conexao, $busca);
                while ($res = mysqli_fetch_array($resultado)) {
                    ?>
                <option value="<?php echo $res['id_estado'] ?>"><?php echo $res["estado"]; ?></option>
                <?php } ?>
            </select>
            </div>

            <div class="form-group">
            <label for="estado">Confirmação</label>
            <select name="confirmacao" class="form-control" id="graduacao">
                <option value="<?php echo $dado['confirmacao']; ?>"><?php echo $dado["confirmacao"]; ?></option>
                <?php if ($dado["confirmacao"] != "sim") { ?>
                <option>sim</option>
                <?php } ?>
                <?php if ($dado["confirmacao"] != "nao") { ?>
                <option>nao</option>
                <?php } ?>
            </select>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary">Salvar mudanças</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php }} ?>