<?php
include "menu.php";
include_once "../db/conexao.php";
include_once "../repositorios/imagemRepositorio.php";
include_once "../repositorios/graduacaoRepositorio.php";

$c = new Conexao();
$conexao = $c->conectar();

$id_usuario = $_SESSION['id_usuario'];

$busca_usuario = "SELECT * FROM usuarios WHERE id_usuario = '$id_usuario'";
$resultado_usuario = mysqli_query($conexao, $busca_usuario);
$usuario = mysqli_fetch_array($resultado_usuario);

if (isset($_SESSION["id_usuario"]) && $_SESSION['nivel'] == "admin") {
    ?>
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="usuario-tab" data-toggle="tab" href="#usuario" role="tab" aria-controls="usuario" aria-selected="true">Usuários</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="postagens-tab" data-toggle="tab" href="#postagens" role="tab" aria-controls="postagens" aria-selected="false">Postagens</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="cursos-tab" data-toggle="tab" href="#cursos" role="tab" aria-controls="cursos" aria-selected="false">Cursos</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="filiados-tab" data-toggle="tab" href="#filiados" role="tab" aria-controls="filiados" aria-selected="false">Filiados</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <?php if (isset($_GET["pagina"]) && $_GET["pagina"] == "filiados") {include_once "admin/filiados.php";}?>
  <div class="tab-pane fade" id="postagens" role="tabpanel" aria-labelledby="postagens-tab">
  <div class="container text-center">
      <h2>Todas as  Postagens</h2>
      <table id="minhaTabela2" class="table table-bordered" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th scope="col">Codigo</th>
				<th scope="col">Titulo</th>
				<th scope="col">Criação</th>
				<th scope="col">Alteração</th>
				<th scope="col">Ações</th>
			</tr>
		</thead>
		<tbody>
			<?php
$busca = "SELECT * FROM postagens";
    $resultado = mysqli_query($conexao, $busca);
    $linha = mysqli_num_rows($resultado);
    if ($linha == '') {
        echo "<h3> Não foram encontrados dados Cadastrados no Banco!! </h3>";
    } else {
        while ($res_postagem = mysqli_fetch_array($resultado)) {
            $id_publicacao = $res_postagem["id_publicacao"];
            $titulo = $res_postagem["titulo"];
            $status = $res_postagem["status"];
            $dataCriacao = $res_postagem["dataCriacao"];
            $dataMudanca = $res_postagem["dataMudanca"];
            ?>
					<tr>
						<th class="font-weight-bold" scope="row"><?php echo $id_publicacao; ?></th>
						<td class="text-capitalize"><?php echo $titulo; ?></td>
						<td class="text-capitalize"><?php echo date_format(date_create($dataCriacao), "d/m/Y"); ?></td>
						<td class="text-capitalize"><?php echo date_format(date_create($dataMudanca), "d/m/Y"); ?></td>
						<td class="text-capitalize">
              <a title="Editar" class="btn btn-info" href="editar_postagem.php?id=<?php echo $id_publicacao; ?>"><i class="fas fa-edit"></i></a>

            <a title="Editar" class="btn btn-info" href="../controllers/mudar_status_postagem.php?id=<?php echo $id_publicacao; ?>">
              <?php echo $status == "aguardando" ? '<i class="fa-regular fa-thumbs-down"></i>' : '<i class="fa-regular fa-thumbs-up"></i>' ?></a>
            </td>
					</tr>
			<?php }
    }?>
		</tbody>
	</table>
    </div>
  </div>
  <div class="tab-pane fade" id="cursos" role="tabpanel" aria-labelledby="cursos-tab">
  <div class="container text-center">
      <h2>Todos os Cursos</h2>
      <div class="row my-4">
        <div class="col">
          <a href="criar_categoria.php" class="btn btn-outline-success">Criar Categoria de curso</a>
        </div>
        <div class="col">
          <a href="criar_curso.php" class="btn btn-outline-success">Criar Curso</a>
        </div>
      </div>
      <table id="minhaTabela3" class="table table-bordered" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th scope="col">Imagem</th>
				<th scope="col">Nome</th>
				<th scope="col">Professor</th>
				<th scope="col">Situação</th>
				<th scope="col">Criação</th>
				<th scope="col">Alteração</th>
				<th scope="col">Ações</th>
			</tr>
		</thead>
		<tbody>
			<?php
$busca = "SELECT * FROM cursos";
    $resultado = mysqli_query($conexao, $busca);
    $linha = mysqli_num_rows($resultado);
    if ($linha == '') {
        echo "<h3> Não foram encontrados dados Cadastrados no Banco!! </h3>";
    } else {
        while ($res_curso = mysqli_fetch_array($resultado)) {
            $id_curso = $res_curso["id_curso"];
            $id_imagem = $res_curso["id_imagem"];
            $nome = $res_curso["nome"];
            $status = $res_curso["situacao"];
            $professor = $res_curso["professor"];
            $dataCriacao = $res_curso["dataCriacao"];
            $dataMudanca = $res_curso["dataMudanca"];

            $res_imagem = ImagemRepositorio::procura_imagem($id_imagem);
            $caminho_imagem = $res_imagem["caminho"];
            $nome_imagem = $res_imagem["nome"];
            ?>
					<tr>
						<th class="font-weight-bold" scope="row"><img class="img-fluid" src="<?php echo $caminho_imagem; ?>" width="50" height="50" alt="<?php echo $nome_imagem; ?>"></th>
						<td class="text-capitalize"><?php echo $nome; ?></td>
						<td class="text-capitalize"><?php echo $professor; ?></td>
						<td class="text-capitalize"><?php echo $status; ?></td>
						<td class="text-capitalize"><?php echo date_format(date_create($dataCriacao), "d/m/Y"); ?></td>
						<td class="text-capitalize"><?php echo date_format(date_create($dataMudanca), "d/m/Y"); ?></td>
						<td class="text-capitalize"></td>
					</tr>
			<?php }
    }?>
		</tbody>
	</table>
    </div>
  </div>
  <div class="tab-pane fade" id="filiados" role="tabpanel" aria-labelledby="filiados-tab">
  <div class="container text-center">
      <h2>Todos os Filiados</h2>
      <table id="minhaTabela4" class="table table-bordered" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th scope="col">Codigo</th>
				<th scope="col">Nome</th>
				<th scope="col">Graduação</th>
				<th scope="col">Dojo</th>
				<th scope="col">Email</th>
				<th scope="col">Telefone</th>
				<th scope="col">Cidade</th>
				<th scope="col">Estado</th>
				<th scope="col">Criação</th>
				<th scope="col">Alteração</th>
				<th scope="col">Ações</th>
			</tr>
		</thead>
		<tbody>
			<?php
$busca = "SELECT * FROM filiados";
    $resultado = mysqli_query($conexao, $busca);
    $linha = mysqli_num_rows($resultado);
    if ($linha == '') {
        echo "<h3> Não foram encontrados dados Cadastrados no Banco!! </h3>";
    } else {
        while ($res_filiado = mysqli_fetch_array($resultado)) {
            $id_filiado = $res_filiado["id_filiado"];
            $nome = $res_filiado["nome"];
            $id_graduacao = $res_filiado["id_graduacao"];
            $dojo = $res_filiado["dojo"];
            $email = $res_filiado["email"];
            $telefone = $res_filiado["telefone"];
            $cidade = $res_filiado["cidade"];
            $id_estado = $res_filiado["id_estado"];

            $res_graduacao = GraduacaoRepositorio::buscarGraduacao($id_graduacao);
            $graduacao = $res_graduacao["graduacao"];

            $busca_estado = "SELECT * FROM estados WHERE id_estado = '$id_estado'";
            $resultado_estado = mysqli_query($conexao, $busca_estado);
            $estado = mysqli_fetch_array($resultado_estado);
            ?>
					<tr>
						<th class="font-weight-bold" scope="row"><?php echo $id_filiado; ?></th>
						<td class="text-capitalize"><?php echo $nome; ?></td>
						<td class="text-capitalize"><?php echo $graduacao; ?></td>
						<td class="text-capitalize"><?php echo $dojo; ?></td>
						<td class="text-capitalize"><?php echo $email; ?></td>
						<td class="text-capitalize"><?php echo $telefone; ?></td>
						<td class="text-capitalize"><?php echo $cidade; ?></td>
						<td class="text-capitalize"><?php echo $estado["estado"] != null ? $estado["estado"] : "" ?></td>
						<td class="text-capitalize"><?php echo date_format(date_create($dataCriacao), "d/m/Y"); ?></td>
						<td class="text-capitalize"><?php echo date_format(date_create($dataMudanca), "d/m/Y"); ?></td>
						<td class="text-capitalize"></td>
					</tr>
			<?php }
    }?>
		</tbody>
	</table>
    </div>
  </div>
</div>

<?php
include "rodape.php";
} else {
    echo "<script language='javascript'>window.location='login.php'; </script>";
}
?>