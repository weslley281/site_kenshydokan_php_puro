<div class="tab-pane fade show active">
  <div class="container text-center">
    <h2>Todos os Cursos</h2>
    <div class="row my-4">
      <div class="col mx-1 my-1">
        <a href="criar_categoria.php" class="btn btn-outline-success btn-lg btn-block">Criar Categoria de curso</a>
      </div>
      <div class="col mx-1 my-1">
        <a href="criar_curso.php" class="btn btn-outline-success btn-lg btn-block">Criar Curso</a>
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
        include_once __DIR__ . "/../../models/cursoModel.php";
        include_once __DIR__ . "/../../models/imagemModel.php";

        $cursos = CursoModel::buscarTodosCursos();
        if (empty($cursos)) {
          echo "<h3> Não foram encontrados dados Cadastrados no Banco!! </h3>";
        } else {
          foreach ($cursos as $res_curso) {
            $id_curso = $res_curso["id_curso"];
            $id_imagem = $res_curso["id_imagem"];
            $nome = $res_curso["nome"];
            $status = $res_curso["situacao"];
            $professor = $res_curso["professor"];
            $dataCriacao = $res_curso["dataCriacao"];
            $dataMudanca = $res_curso["dataMudanca"];

            $res_imagem = Imagem::procura_imagem($id_imagem);
            $caminho_imagem = $res_imagem ? $res_imagem["caminho"] : '';
            $nome_imagem = $res_imagem ? $res_imagem["nome"] : '';
        ?>
            <tr>
              <th class="font-weight-bold" scope="row"><img class="img-fluid" src="<?php echo htmlspecialchars($caminho_imagem); ?>" width="50" height="50" alt="<?php echo htmlspecialchars($nome_imagem); ?>"></th>
              <td class="text-capitalize"><?php echo htmlspecialchars($nome); ?></td>
              <td class="text-capitalize"><?php echo htmlspecialchars($professor); ?></td>
              <td class="text-capitalize"><?php echo htmlspecialchars($status); ?></td>
              <td class="text-capitalize"><?php echo date_format(date_create($dataCriacao), "d/m/Y"); ?></td>
              <td class="text-capitalize"><?php echo date_format(date_create($dataMudanca), "d/m/Y"); ?></td>
              <td class="text-capitalize">
                <div class="form-group">
                  <a class="btn btn-success" href="" title="Emitir Certificado"><i class="fa-solid fa-graduation-cap"></i></a>
                </div>
                <div class="form-group">
                  <a class="btn btn-success" href="criar_aula.php?id=<?php echo $id_curso; ?>" title="Adcionar Aulas"><i class="fa-solid fa-person-chalkboard"></i></a>
                </div>
                <div class="form-group">
                  <a class="btn btn-primary" href="editar_curso.php?id=<?php echo $id_curso; ?>" title="Editar Curso"><i class="fa-solid fa-pen-to-square"></i></a>
                </div>
                <div class="form-group">
                  <button class="btn btn-danger" data-toggle="modal" data-target="#modalExcluirCurso<?php echo $id_curso; ?>" title="Excluir curso"><i class="fa-regular fa-calendar-xmark"></i></button>
                </div>
              </td>
            </tr>

            <!-- Modal -->
            <div class="modal fade" id="modalExcluirCurso<?php echo $id_curso; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Você tem certeza que deseja escluir:</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <p><?php echo htmlspecialchars($nome); ?></p>
                  </div>
                  <div class="modal-footer">
                    <form action="../controllers/cursoController" method="post">
                      <input type="hidden" name="tipo" value="excluir">
                      <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>">

                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                      <button type="submit" class="btn btn-danger">Sim</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
        <?php }
        } ?>
      </tbody>
    </table>
  </div>
</div>