<?php
$page_title = "Editar Postagem";
include __DIR__ . "/menu.php";
include __DIR__ . "/_perfil_auth.php";

// Check if ID is set
if (!isset($_GET["id"])) {
    // Redirect or show an error if the ID is not provided
    header("Location: suas_postagens.php");
    exit();
}

$id_publicacao = $_GET["id"];

// Fetch the post using a prepared statement to prevent SQL injection
$query = "SELECT * FROM postagens WHERE id_publicacao = ?";
$stmt = $conexao->prepare($query);
$stmt->bind_param("i", $id_publicacao);
$stmt->execute();
$result = $stmt->get_result();
$postagem = $result->fetch_assoc();
$stmt->close();

// Check if the post exists and if the current user is the author
if (!$postagem || $postagem["id_usuario"] != $_SESSION["id_usuario"]) {
    echo "<script language='javascript'>window.alert('Você não pode fazer isso'); </script>";
    echo "<script language='javascript'>window.location='suas_postagens.php'; </script>";
    exit();
}
?>

<body>
    <div class="container mt-5">
        <div class="container">
            <div class="row">

                <?php include __DIR__ . "/_perfil_menu.php"; ?>

                <div class="col-lg-9">
                    <div class="text-center mt-4">
                        <h1><strong>Editar Postagem</strong></h1>
                    </div>
                    
                    <form class="mb-3" action="../../controllers/postagemController.php" method="POST">
                        <input type="hidden" value="editar" name="tipo">
                        <input type="hidden" value="<?php echo $id_publicacao; ?>" name="id_publicacao">

                        <div class="form-group">
                            <label for="titulo">Título</label>
                            <input id="titulo" class="form-control" type="text" value="<?php echo htmlspecialchars($postagem["titulo"]); ?>" name="titulo">
                        </div>

                        <div class="form-group">
                            <label for="conteudo">Conteúdo</label>
                            <textarea id="conteudo" name="conteudo" rows="20"><?php echo htmlspecialchars($postagem["conteudo"]); ?></textarea>
                        </div>

                        <div class="form-group">
                            <input class="btn btn-success" type="submit" name="salvar" value="Salvar Alterações">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php include __DIR__ . "/rodape.php"; ?>
