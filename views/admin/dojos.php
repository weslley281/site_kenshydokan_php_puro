<?php
include_once "../../config/conexao.php";
include_once "../../Classes/Dojo.php";
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gerenciar Dojôs</h1>
        <a href="criar_dojo.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Adicionar Novo Dojô</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lista de Dojôs Afiliados</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Logo</th>
                            <th>Nome Fantasia</th>
                            <th>CNPJ</th>
                            <th>Responsável</th>
                            <th>Telefone</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once __DIR__ . '/../../models/dojoModel.php';
                        $dojoRepositorio = new DojoModel();
                        $dojos = $dojoRepositorio->listarDojos();
                        foreach ($dojos as $dojo) {
                        ?>
                            <tr>
                                <td><img src="../../img/<?php echo htmlspecialchars($dojo['imagem']); ?>" alt="Logo" width="50"></td>
                                <td><?php echo htmlspecialchars($dojo['nome_fantasia']); ?></td>
                                <td><?php echo htmlspecialchars($dojo['cnpj']); ?></td>
                                <td><?php echo htmlspecialchars($dojo['nome_responsavel'] ?? 'Não definido'); ?></td>
                                <td><?php echo htmlspecialchars($dojo['celular'] ?? $dojo['telefone']); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $dojo['status'] == 'ativo' ? 'success' : 'danger'; ?>">
                                        <?php echo ucfirst($dojo['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="editar_dojo.php?id=<?php echo $dojo['id']; ?>" class="btn btn-warning btn-circle btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="btn btn-danger btn-circle btn-sm" data-toggle="modal" data-target="#excluirModal<?php echo $dojo['id']; ?>" title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </a>

                                    <!-- Modal de Exclusão -->
                                    <div class="modal fade" id="excluirModal<?php echo $dojo['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Confirmar Exclusão</h5>
                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">Tem certeza que deseja excluir o dojô "<?php echo htmlspecialchars($dojo['nome_fantasia']); ?>"?</div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                                    <form action="../../controllers/dojoController.php" method="post">
                                                        <input type="hidden" name="id" value="<?php echo $dojo['id']; ?>">
                                                        <input type="hidden" name="tipo" value="excluir">
                                                        <button type="submit" class="btn btn-danger">Excluir</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>