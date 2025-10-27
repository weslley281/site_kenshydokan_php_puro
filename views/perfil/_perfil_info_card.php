<div id="perfil-info-app">
    <button @click="infoVisible = !infoVisible" class="btn btn-info mb-3">
        {{ buttonText }}
    </button>
    <div v-if="infoVisible" class="text-center mt-3 mb-3">
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

                        <?php
                        if ($filiado["confirmacao"] == "sim" && $usuario["nivel"] != "aluno") {
                            echo '<div class="text-center mb-3">';
                            echo '<a href="../../controllers/gerar_carteirinha.php" target="_blank" class="btn btn-primary">Gerar Carteirinha de Filiado</a>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>