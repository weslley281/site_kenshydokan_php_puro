<?php
$page_title = "Editar Postagem";
include __DIR__ . "/menu.php";
include_once __DIR__ . "/../../models/publicacaoModel.php";

// Check if ID is set
if (!isset($_GET["id"])) {
    header("Location: index.php?pagina=postagens");
    exit();
}

$id_publicacao = $_GET["id"];
$postagem = Publicacao::buscarPostagemPorId($id_publicacao);

// Check if the post exists and if the user is authorized (author or admin)
if (!$postagem || ($postagem["id_usuario"] != $_SESSION["id_usuario"] && $_SESSION["nivel"] != "admin")) {
    echo "<script language='javascript'>window.alert('Você não tem permissão para editar esta postagem.'); </script>";
    echo "<script language='javascript'>window.location='index.php?pagina=postagens'; </script>";
    exit();
}
?>

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="font-weight-bold text-dark mb-0">Editar Postagem</h2>
        <a href="index.php?pagina=postagens" class="btn btn-outline-secondary font-weight-bold rounded-pill shadow-sm px-4">
            <i class="fas fa-arrow-left mr-2"></i> Voltar para Postagens
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-body p-4">
            <h5 class="text-danger font-weight-bold mb-4"><i class="fa-solid fa-pen-to-square mr-2"></i>Conteúdo da Publicação</h5>
            
            <form action="../../controllers/postagemController.php" method="POST">
                <input type="hidden" value="editar" name="tipo">
                <input type="hidden" value="<?php echo $id_publicacao; ?>" name="id_publicacao">

                <div class="form-group mb-4">
                    <label for="titulo" class="text-secondary small font-weight-bold text-uppercase">Título da Postagem</label>
                    <input id="titulo" class="form-control form-control-lg bg-light border-0 shadow-sm" type="text" value="<?php echo htmlspecialchars($postagem["titulo"]); ?>" name="titulo" required>
                </div>

                <div class="form-group mb-4">
                    <label for="conteudo" class="text-secondary small font-weight-bold text-uppercase">Conteúdo</label>
                    <textarea id="conteudo" name="conteudo" class="form-control bg-light border-0 shadow-sm" rows="15"><?php echo htmlspecialchars($postagem["conteudo"]); ?></textarea>
                </div>

                <div class="mt-4">
                    <button type="submit" name="salvar" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-5 py-2 mr-2">
                        <i class="fas fa-save mr-2"></i> Salvar Alterações
                    </button>
                    <a href="index.php?pagina=postagens" class="btn btn-light font-weight-bold rounded-pill shadow-sm px-5 py-2 border">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . "/rodape.php"; ?>
