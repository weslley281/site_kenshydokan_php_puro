<div id="perfil-info-app">
    <button @click="infoVisible = !infoVisible" class="btn btn-outline-danger rounded-pill px-4 mb-4 shadow-sm font-weight-bold">
        <i class="fa-solid" :class="infoVisible ? 'fa-eye-slash' : 'fa-eye'"></i> {{ buttonText }}
    </button>
    
    <div v-if="infoVisible" class="mt-2 mb-4">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="row no-gutters">
                <!-- Seção da Foto (Esquerda no desktop, topo no mobile) -->
                <div class="col-md-4 bg-dark text-white text-center py-4 px-3 d-flex flex-column align-items-center justify-content-center">
                    <img class="rounded-circle shadow border border-danger mb-3" 
                         src="<?php echo (!empty($imagem) && isset($imagem['nome'])) ? '../../img/' . $imagem['nome'] : '../../arquivos/sem_imagem.png'; ?>" 
                         alt="Foto de <?php echo $usuario["nome"]; ?>" 
                         style="width: 130px; height: 130px; object-fit: cover; border-width: 4px !important;">
                    
                    <h5 class="font-weight-bold mb-1 text-white"><?php echo $usuario["nome"]; ?></h5>
                    <span class="badge badge-danger text-uppercase px-2 py-1 mt-1 small font-weight-bold" style="letter-spacing: 0.5px; background-color: var(--primary-red);">
                        <?php echo $usuario["nivel"]; ?>
                    </span>
                </div>
                
                <!-- Seção das Informações (Direita) -->
                <div class="col-md-8 p-4">
                    <h5 class="font-weight-bold text-danger mb-3 border-bottom pb-2">
                        <i class="fa-solid fa-address-card mr-2"></i><?php echo "$estaFiliado"; ?>
                    </h5>
                    
                    <div class="row text-dark" style="font-size: 0.95rem;">
                        <div class="col-12 mb-2">
                            <i class="fa-solid fa-graduation-cap text-muted mr-2" style="width: 20px;"></i>
                            <strong>Graduações:</strong>
                            <?php if (!empty($filiadoGraduacoes)): ?>
                                <ul class="list-unstyled pl-4 mb-0 text-capitalize">
                                    <?php foreach ($filiadoGraduacoes as $fg): ?>
                                        <li><i class="fa-solid fa-chevron-right text-danger mr-1" style="font-size: 0.8rem;"></i> <?php echo htmlspecialchars($fg['graduacao_nome']) . " (" . htmlspecialchars($fg['arte_nome']) . ")"; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <span><?php echo htmlspecialchars($graduacao["graduacao"] ?? 'Sem registro'); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="col-12 mb-2">
                            <i class="fa-solid fa-gopuran text-muted mr-2" style="width: 20px;"></i>
                            <strong>Dojo:</strong> <?php echo $filiado["dojo"]; ?>
                        </div>
                        <div class="col-12 mb-2">
                            <i class="fa-solid fa-barcode text-muted mr-2" style="width: 20px;"></i>
                            <strong>Código de Filiação:</strong> <?php echo $filiado["codigo"]; ?>
                        </div>
                        <div class="col-12 mb-2">
                            <i class="fa-solid fa-envelope text-muted mr-2" style="width: 20px;"></i>
                            <strong>E-mail:</strong> <?php echo $usuario["email"]; ?>
                        </div>
                        <div class="col-12 mb-3">
                            <i class="fa-solid fa-phone text-muted mr-2" style="width: 20px;"></i>
                            <strong>Telefone:</strong> <?php echo $usuario["telefone"]; ?>
                        </div>
                    </div>

                    <?php
                    if ($filiado["confirmacao"] == "sim" && $usuario["nivel"] != "aluno") {
                        echo '<div class="mt-4 border-top pt-3 text-center text-md-left">';
                        echo '<a href="../../controllers/gerar_carteirinha.php" target="_blank" class="btn btn-danger rounded-pill shadow-sm font-weight-bold px-4"><i class="fa-solid fa-id-card mr-2"></i>Gerar Carteirinha de Filiado</a>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>