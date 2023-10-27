<div class="tab-pane fade show active">
  <div class="container text-center">
      <h2>Todos os Cursos</h2>
      <div class="row my-4">
        <div class="col">
          <a href="../views/criar_categoria.php" class="btn btn-outline-success">Criar Categoria de curso</a>
        </div>
        <div class="col">
          <a href="../views/criar_curso.php" class="btn btn-outline-success">Criar Curso</a>
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