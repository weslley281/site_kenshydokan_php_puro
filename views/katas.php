<?php
include "menu.php";
include_once "../db/conexao.php";
$c = new Conexao();
$conexao = $c->conectar();

?>

<div class="container-fluid">
    <!--
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item" src="https://www.youtube.com/watch?v=Bzek4rLW8dg&t=3s" allowfullscreen></iframe>
            </div>
        </div>
    </div>
                -->
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="embed-responsive embed-responsive-16by9">
                <video id="my-video" class="video-js embed-responsive-item" controls preload="auto" data-setup="{}">
                    <source src="https://www.youtube.com/watch?v=Bzek4rLW8dg&t=3s" type="video/mp4">
                    Seu navegador não suporta o elemento de vídeo.
                </video>
            </div>
        </div>
    </div>
</div>

<?php include "rodape.php"; ?>