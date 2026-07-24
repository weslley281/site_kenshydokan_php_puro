<?php
$page_title = "Editar Perfil";
include __DIR__ . "/menu.php";
include __DIR__ . "/_perfil_auth.php";
?>

<style>
  .profile-left-card {
      background: linear-gradient(135deg, #1f1f1f 0%, #3d0000 100%);
      border-radius: 16px;
  }
  .avatar-upload-container {
      position: relative;
      width: 140px;
      height: 140px;
  }
  .avatar-preview {
      width: 140px;
      height: 140px;
      object-fit: cover;
      border: 4px solid var(--primary-red);
  }
  .custom-file-label-btn {
      cursor: pointer;
      display: inline-block;
      padding: 8px 20px;
      background: rgba(255,255,255,0.1);
      color: #fff;
      border-radius: 30px;
      font-size: 0.85rem;
      font-weight: bold;
      transition: all 0.2s ease;
      border: 1px dashed rgba(255,255,255,0.3);
  }
  .custom-file-label-btn:hover {
      background: rgba(255,255,255,0.2);
      border-color: #fff;
  }
  .form-card {
      border-radius: 16px;
      border: 0 !important;
  }
  .char-counter {
      font-size: 0.75rem;
      color: #6c757d;
      text-align: right;
  }
</style>

<div class="container py-5">
    <div class="row">
        <!-- Menu do Perfil Lateral (Já é responsivo e estilizado) -->
        <?php include __DIR__ . "/_perfil_menu.php"; ?>

        <!-- Conteúdo Principal -->
        <div class="col-lg-9 mt-4 mt-lg-0">
            <div class="row">
                
                <!-- Coluna da Esquerda (Mini-Perfil & Upload da Foto) -->
                <div class="col-md-4 mb-4">
                    <div class="card profile-left-card text-white text-center py-4 px-3 shadow-sm border-0">
                        <div class="card-body p-0 d-flex flex-column align-items-center">
                            
                            <!-- Preview da Imagem -->
                            <div class="avatar-upload-container mb-3">
                                <?php
                                $caminho_foto = "../../img/sem_foto.png";
                                if ($imagem && !empty($imagem["caminho"])) {
                                    $caminho_foto = "../" . $imagem["caminho"];
                                }
                                ?>
                                <img id="imagePreview" class="rounded-circle avatar-preview shadow-lg" src="<?php echo $caminho_foto; ?>" alt="Preview de <?php echo htmlspecialchars($usuario["nome"]); ?>">
                            </div>
                            
                            <h5 class="font-weight-bold mb-1 text-white text-capitalize"><?php echo htmlspecialchars($usuario["nome"]); ?></h5>
                            <span class="badge badge-danger text-uppercase px-2 py-1 mt-1 small font-weight-bold" style="letter-spacing: 0.5px; background-color: var(--primary-red); font-size: 0.7rem;">
                                <?php 
                                $niveis = ['admin' => 'Administrador', 'sensei' => 'Sensei', 'aluno' => 'Aluno', 'dojo' => 'Representante de Dojô'];
                                echo isset($niveis[$usuario["nivel"]]) ? $niveis[$usuario["nivel"]] : ucfirst($usuario["nivel"]);
                                ?>
                            </span>

                            <hr class="w-100 border-secondary my-4" style="opacity: 0.2;">

                            <!-- Form de Atualização de Foto -->
                            <form action="../../controllers/usuarioController.php" method="post" enctype="multipart/form-data" class="w-100">
                                <input type="hidden" value="editar_imagem" name="tipo">
                                <input type="hidden" value="<?php echo $usuario["id_imagem"]; ?>" name="id_imagem">
                                <input type="hidden" value="<?php echo $usuario["id_usuario"]; ?>" name="id_usuario">

                                <div class="form-group mb-3">
                                    <label for="imagem" class="custom-file-label-btn w-100 text-center">
                                        <i class="fa-solid fa-cloud-arrow-up mr-2"></i>Escolher Foto
                                    </label>
                                    <input type="file" id="imagem" name="imagem" accept="image/*" class="d-none" required>
                                    <div id="file-name-display" class="small text-muted-light mt-1 text-truncate px-2" style="font-size: 0.75rem;">Nenhum arquivo selecionado</div>
                                </div>

                                <button type="submit" class="btn btn-sm btn-danger rounded-pill btn-block font-weight-bold py-2 shadow-sm" name="atualizar">
                                    <i class="fa-solid fa-rotate mr-1"></i>Atualizar Foto
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Coluna da Direita (Dados Gerais & Alterar Senha) -->
                <div class="col-md-8">
                    
                    <!-- Card de Dados Gerais -->
                    <div class="card form-card shadow-sm mb-4">
                        <div class="card-body p-4 p-md-5">
                            <h5 class="font-weight-bold text-danger mb-4 border-bottom pb-2">
                                <i class="fa-solid fa-user-pen mr-2"></i>Dados Cadastrais
                            </h5>

                            <form action="../../controllers/usuarioController.php" method="post" class="text-left">
                                <input type="hidden" value="edidar" name="tipo">
                                <input type="hidden" value="<?php echo $usuario["id_usuario"]; ?>" name="id_usuario">
                                <input type="hidden" value="<?php echo $usuario["id_imagem"]; ?>" name="id_imagem">
                                <input type="hidden" value="<?php echo $usuario["id_fil"]; ?>" name="id_fil">
                                <input type="hidden" value="<?php echo $usuario["nivel"]; ?>" name="nivel">

                                <div class="form-group mb-3">
                                    <label for="nome" class="text-secondary small font-weight-bold text-uppercase">Nome Completo <span class="text-danger">*</span></label>
                                    <input id="nome" type="text" class="form-control bg-light border-0 shadow-sm" name="nome" value="<?php echo htmlspecialchars($usuario["nome"]); ?>" required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="email" class="text-secondary small font-weight-bold text-uppercase">E-mail (Login)</label>
                                    <input id="email" type="email" class="form-control bg-light border-0 shadow-sm" name="email" value="<?php echo htmlspecialchars($usuario["email"]); ?>" readonly>
                                    <small class="text-muted" style="font-size: 0.75rem;"><i class="fa-solid fa-lock mr-1"></i>O e-mail não pode ser alterado para segurança da sua conta.</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="telefone" class="text-secondary small font-weight-bold text-uppercase">Telefone / WhatsApp <span class="text-danger">*</span></label>
                                    <input id="telefone" type="text" class="form-control bg-light border-0 shadow-sm" name="telefone" value="<?php echo htmlspecialchars($usuario["telefone"]); ?>" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" required>
                                </div>

                                <!-- NOVO CAMPO: Descrição / Biografia do Atleta -->
                                <div class="form-group mb-4">
                                    <label for="descricao" class="text-secondary small font-weight-bold text-uppercase">Sobre o Atleta (Biografia)</label>
                                    <textarea id="descricao" class="form-control bg-light border-0 shadow-sm" name="descricao" rows="4" maxlength="600" placeholder="Escreva uma breve biografia sobre você, sua trajetória nas artes marciais, conquistas ou graduações..."><?php echo htmlspecialchars($usuario["descricao"] ?? ''); ?></textarea>
                                    <div class="char-counter mt-1">
                                        <span id="char-count">0</span> / 600 caracteres
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-danger rounded-pill px-5 py-2 font-weight-bold shadow-sm" name="editar">
                                    <i class="fa-solid fa-floppy-disk mr-2"></i>Salvar Alterações
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Card de Alterar Senha -->
                    <div class="card form-card shadow-sm">
                        <div class="card-body p-4 p-md-5">
                            <h5 class="font-weight-bold text-danger mb-4 border-bottom pb-2">
                                <i class="fa-solid fa-key mr-2"></i>Alterar Senha
                            </h5>

                            <form action="../../controllers/editar_senha.php" method="post" class="text-left">
                                <input type="hidden" value="<?php echo $usuario["id_usuario"]; ?>" name="id_usuario">
                                <input type="hidden" value="editar_senha" name="tipo">

                                <div class="form-group mb-3">
                                    <label for="nova_senha" class="text-secondary small font-weight-bold text-uppercase">Nova Senha <span class="text-danger">*</span></label>
                                    <input id="nova_senha" type="password" class="form-control bg-light border-0 shadow-sm" name="nova_senha" placeholder="Digite a nova senha" required>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="senha2" class="text-secondary small font-weight-bold text-uppercase">Repetir Nova Senha <span class="text-danger">*</span></label>
                                    <input id="senha2" type="password" class="form-control bg-light border-0 shadow-sm" name="senha2" placeholder="Repita a nova senha" required>
                                </div>

                                <button type="submit" class="btn btn-outline-danger rounded-pill px-5 py-2 font-weight-bold shadow-sm">
                                    <i class="fa-solid fa-lock mr-2"></i>Salvar Senha
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Exibir nome do arquivo de imagem selecionado
    const fileInput = document.getElementById('imagem');
    const fileNameDisplay = document.getElementById('file-name-display');
    
    if (fileInput && fileNameDisplay) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files.length > 0) {
                fileNameDisplay.textContent = this.files[0].name;
            } else {
                fileNameDisplay.textContent = "Nenhum arquivo selecionado";
            }
        });
    }

    // Contador de caracteres para a descrição/biografia
    const bioTextarea = document.getElementById('descricao');
    const charCountSpan = document.getElementById('char-count');
    
    if (bioTextarea && charCountSpan) {
        // Função para atualizar o contador
        const updateCounter = () => {
            charCountSpan.textContent = bioTextarea.value.length;
        };
        
        // Inicializa o contador
        updateCounter();
        
        // Vincula eventos de digitação
        bioTextarea.addEventListener('input', updateCounter);
    }
});
</script>

<?php include __DIR__ . "/rodape.php"; ?>