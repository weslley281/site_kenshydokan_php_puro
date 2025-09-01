<?php
include_once __DIR__ . "/../../repositorios/exameGraduacaoRepositorio.php";
$exameRepositorio = new ExameGraduacaoRepositorio();
$exames = $exameRepositorio->listarTodos();
?>

<div class="container">
    <h2 class="text-center">Gerenciar Exames de Graduação</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Documento</th>
                <th>Graduação Atual</th>
                <th>Graduação Pretendida</th>
                <th>Professor</th>
                <th>Email</th>
                <th>Situação</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($exames as $exame) : ?>
                <tr>
                    <td><?php echo $exame['id']; ?></td>
                    <td><?php echo $exame['nome']; ?></td>
                    <td><?php echo $exame['documento']; ?></td>
                    <td><?php echo $exame['graduacao_atual']; ?></td>
                    <td><?php echo $exame['graduacao_pretendida']; ?></td>
                    <td><?php echo $exame['professor']; ?></td>
                    <td><?php echo $exame['email']; ?></td>
                    <td><?php echo $exame['situacao']; ?></td>
                    <td>
                        <a href="admin/editar_exame.php?id=<?php echo $exame['id']; ?>" class="btn btn-primary">Editar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
