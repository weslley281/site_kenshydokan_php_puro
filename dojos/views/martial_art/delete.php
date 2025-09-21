<?php if (isset($_SESSION["user_id"]) && $_SESSION["type"] == "admin") { ?>
    <div class="container mt-5 mb-5">
        <h1>Deletar Arte Marcial</h1>
        <div class="alert alert-danger" role="alert">
            <p>Você tem certeza que deseja deletar essa arte marcial <strong>"<?php echo $martial_art["name"] ?></strong>"</p>
        </div>

        <form action="./controllers/MartialArtController.php?action=delete" method="post" class="form-group">
            <input type="hidden" name="id" value="<?php echo $_GET["id"] ?>">
            <button type="submit" class="btn btn-danger">Delete</button>
            <a href="./index.php?page=martial_arts" class="btn btn-light">Cancelar</a>
        </form>
    </div>
<?php } else {
    echo "<center><strong><h1>Você não Tem permição para isso</h1></strong></center>";
    echo "<script>";
    echo "setTimeout(function() { window.location.href = './index.php?page=dashboard'; }, 3000);";
    echo "</script>";
} ?>