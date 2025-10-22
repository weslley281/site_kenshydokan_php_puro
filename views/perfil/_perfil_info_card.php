<?php // _perfil_info_card.php 
?>
<div id="esconder" class="text-center mt-3 mb-3">
    <div class="row">
        <!-- card do perfil -->
        <div class="col-5 mb-4">
            <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="<?php echo (!empty($imagem) && isset($imagem['nome'])) ? '../../img/' . $imagem['nome'] : '../../arquivos/sem_imagem.png'; ?>" alt="">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $usuario["nome"]; ?></h5>
                </div>
            </div>
        </div>
        <!-- mais informações -->
        <div class="col mb-4">
            <div class="card">
                <h5 class="card-header"><?php echo "$estaFiliado"; ?></h5>
                <div class="card-body">
                    <h5 class="card-title">Sua graduação é <?php echo $graduacao["graduacao"]; ?></h5>
                    <p class="card-text">Dojo: <?php echo $filiado["dojo"]; ?></p>
                    <p class="card-text">Filiação: <?php echo $filiado["codigo"]; ?></p>
                    <p class="card-text">E-mail: <?php echo $usuario["email"]; ?></p>
                    <p class="card-text">Telefone: <?php echo $usuario["telefone"]; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Fim dados do perfil -->