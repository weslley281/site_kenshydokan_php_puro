<?php
// views/admin/certificados_upload.php
include_once __DIR__ . "/../../models/certificadoUploadModel.php";
include_once __DIR__ . "/../../models/filiadoModel.php";

$certUploadRepo = new CertificadoUploadModel();
$filiadoRepo = new FiliadoModel();

$certificados = $certUploadRepo->listarTodos();
// Apenas filiados confirmados para envio
$filiados = $filiadoRepo->listarFiliadosAtivos(); 
?>

<div class="tab-pane fade show active">
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Gerenciar Certificados em Imagem</h2>
      <button type="button" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-4" data-toggle="modal" data-target="#modalAdicionarCertificadoUpload">
        <i class="fas fa-upload mr-2"></i> Enviar Certificado
      </button>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
      <div class="card-body p-4">
        <div class="table-responsive">
          <table id="tabelaCertificadosUpload" class="table table-hover align-middle" width="100%" cellspacing="0">
            <thead>
              <tr class="text-secondary small font-weight-bold border-bottom">
                <th scope="col" style="width: 80px;">Código</th>
                <th scope="col">Filiado</th>
                <th scope="col">Dojô</th>
                <th scope="col">Graduação</th>
                <th scope="col">Título do Certificado</th>
                <th scope="col">Data de Envio</th>
                <th scope="col" class="text-center" style="width: 150px;">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($certificados)): ?>
                <?php foreach ($certificados as $cert): 
                  $id = $cert['id'];
                  $id_filiado = $cert['id_filiado'];
                  $nome = $cert['nome'];
                  $codigo_filiado = $cert['codigo'];
                  $dojo = $cert['dojo'];
                  $graduacao = $cert['graduacao'] ?? 'Sem registro';
                  $titulo = $cert['titulo'];
                  $imagem = $cert['imagem'];
                  $data_upload = $cert['data_upload'];
                ?>
                  <tr>
                    <td class="align-middle font-weight-bold text-secondary">#<?php echo htmlspecialchars($codigo_filiado ?? $id_filiado); ?></td>
                    <td class="align-middle font-weight-bold text-dark text-capitalize"><?php echo htmlspecialchars($nome ?? ''); ?></td>
                    <td class="align-middle text-dark small text-capitalize"><?php echo htmlspecialchars($dojo ?? ''); ?></td>
                    <td class="align-middle text-dark small text-capitalize"><?php echo htmlspecialchars($graduacao ?? ''); ?></td>
                    <td class="align-middle text-dark font-weight-bold"><?php echo htmlspecialchars($titulo ?? ''); ?></td>
                    <td class="align-middle text-muted small"><?php echo date("d/m/Y", strtotime($data_upload)); ?></td>
                    <td class="align-middle text-center">
                      <!-- Visualizar Imagem -->
                      <a href="../img/certificados/<?php echo htmlspecialchars($imagem); ?>" target="_blank" class="btn btn-sm btn-outline-success border-0 rounded-circle mr-1" title="Visualizar Certificado" style="width: 32px; height: 32px; padding: 5px 0;">
                        <i class="fa-solid fa-eye"></i>
                      </a>

                      <!-- Excluir -->
                      <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle" data-toggle="modal" data-target="#modalExcluirCertificadoUpload<?php echo $id; ?>" title="Excluir Certificado" style="width: 32px; height: 32px; padding: 5px 0;">
                        <i class="fa-solid fa-trash-can"></i>
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php if (!empty($certificados)): ?>
  <?php foreach ($certificados as $cert): 
    $id = $cert['id'];
    $nome = $cert['nome'];
    $titulo = $cert['titulo'];
    $imagem = $cert['imagem'];
  ?>
    <!-- Modal Excluir Certificado -->
    <div class="modal fade" id="modalExcluirCertificadoUpload<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="excluirLabel<?php echo $id; ?>" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg text-left">
          <div class="modal-header bg-danger text-white border-0 py-3">
            <h5 class="modal-title font-weight-bold" id="excluirLabel<?php echo $id; ?>">
              <i class="fa-solid fa-triangle-exclamation mr-2"></i> Confirmar Exclusão
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body p-4 text-center">
            <p class="lead mb-2">Tem certeza que deseja excluir o certificado de?</p>
            <h5 class="font-weight-bold text-danger mb-2"><?php echo htmlspecialchars($nome); ?></h5>
            <p class="text-secondary font-weight-bold mb-3">"<?php echo htmlspecialchars($titulo); ?>"</p>
            
            <img src="../img/certificados/<?php echo htmlspecialchars($imagem); ?>" class="img-fluid rounded border shadow-sm mb-3" style="max-height: 150px;" alt="Prévia">
            
            <p class="text-muted small">Esta ação apagará a imagem permanentemente e não poderá ser desfeita.</p>
          </div>
          <div class="modal-footer border-0 bg-light py-3 text-center d-flex justify-content-center">
            <button type="button" class="btn btn-secondary px-4 rounded-pill font-weight-bold mr-2" data-dismiss="modal">Cancelar</button>
            <form action="../../controllers/certificadoUploadController.php" method="post" class="d-inline">
              <input type="hidden" name="tipo" value="excluir">
              <input type="hidden" name="id" value="<?php echo $id; ?>">
              <button type="submit" class="btn btn-danger px-4 rounded-pill font-weight-bold shadow-sm">Excluir</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<!-- Modal Adicionar Certificado -->
<div class="modal fade" id="modalAdicionarCertificadoUpload" role="dialog" aria-labelledby="adicionarLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-danger text-white border-0 py-3">
        <h5 class="modal-title font-weight-bold" id="adicionarLabel">
          <i class="fa-solid fa-upload mr-2"></i> Enviar Certificado em Imagem
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="../../controllers/certificadoUploadController.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="tipo" value="inserir">
        
        <div class="modal-body p-4 text-left">
          <div class="form-group mb-3">
            <label for="id_filiado_upload" class="text-secondary small font-weight-bold text-uppercase">Selecionar Filiado</label>
            <select class="form-control form-control-lg bg-light border-0 shadow-sm js-select2-filiado-upload" id="id_filiado_upload" name="id_filiado" required>
              <option value="">Selecione um filiado...</option>
              <?php foreach ($filiados as $f): ?>
                <option value="<?php echo $f['id_filiado']; ?>">
                  <?php echo htmlspecialchars($f['nome']) . ' (Código: ' . htmlspecialchars($f['codigo'] ?? $f['id_filiado']) . ')'; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group mb-3">
            <label for="titulo_upload" class="text-secondary small font-weight-bold text-uppercase">Título do Certificado</label>
            <input type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" id="titulo_upload" name="titulo" placeholder="Ex: Certificado de Faixa Preta 1º Dan" required>
          </div>

          <div class="form-group mb-3">
            <label for="certificado_imagem" class="text-secondary small font-weight-bold text-uppercase">Imagem do Certificado</label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="certificado_imagem" name="imagem" accept="image/*" required>
              <label class="custom-file-label form-control-lg bg-light border-0 shadow-sm" for="certificado_imagem" style="height: auto; padding: 10px 15px;">Escolher imagem...</label>
            </div>
            <small class="text-muted d-block mt-2">Formatos aceitos: JPG, JPEG, PNG e WEBP. A imagem será otimizada automaticamente.</small>
          </div>

          <!-- Prévia da Imagem Otimizada -->
          <div class="form-group text-center mb-0 mt-3">
            <img id="certPreview" src="#" alt="Prévia do Certificado" class="img-fluid rounded border shadow-sm" style="display: none; max-height: 200px; object-fit: contain;">
          </div>
        </div>

        <div class="modal-footer border-0 bg-light py-3 d-flex justify-content-end">
          <button type="button" class="btn btn-secondary px-4 rounded-pill font-weight-bold mr-2" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger px-4 rounded-pill font-weight-bold shadow-sm">Enviar Certificado</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        if (!$.fn.DataTable.isDataTable('#tabelaCertificadosUpload')) {
            $('#tabelaCertificadosUpload').DataTable({
                "order": [[5, "desc"]], 
                "pageLength": 10,
                "searching": true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json"
                }
            });
        }
    }

    if (typeof $ !== 'undefined' && $.fn.select2) {
        // Desativa a imposição de foco do Bootstrap modal para evitar loops infinitos de foco com o Select2
        if ($.fn.modal && $.fn.modal.Constructor) {
            $.fn.modal.Constructor.prototype._enforceFocus = function() {};
        }

        // Inicializar Select2 ao abrir o modal
        $('#modalAdicionarCertificadoUpload').on('shown.bs.modal', function () {
            var $select = $('#id_filiado_upload');
            if (!$select.hasClass('select2-hidden-accessible')) {
                $select.select2({
                    dropdownParent: $('#modalAdicionarCertificadoUpload'),
                    language: 'pt-BR',
                    width: '100%'
                });
            }
        });
    }

    // Lógica para nome do arquivo na custom-file input e pré-visualização comprimida
    $('#certificado_imagem').change(function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName || 'Escolher imagem...');

        if (this.files && this.files[0]) {
            const file = this.files[0];
            if (!file.type.startsWith('image/')) {
                alert("Erro: O arquivo selecionado não é uma imagem válida.");
                $(this).val('');
                $(this).next('.custom-file-label').html('Escolher imagem...');
                $('#certPreview').hide();
                return;
            }

            const reader = new FileReader();
            const input = this;
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    const canvas = document.createElement('canvas');
                    let width = img.width;
                    let height = img.height;
                    
                    // Limite ideal para leitura do certificado de forma legível
                    const MAX_WIDTH = 1200;
                    const MAX_HEIGHT = 1200;

                    if (width > height) {
                        if (width > MAX_WIDTH) {
                            height *= MAX_WIDTH / width;
                            width = MAX_WIDTH;
                        }
                    } else {
                        if (height > MAX_HEIGHT) {
                            width *= MAX_HEIGHT / height;
                            height = MAX_HEIGHT;
                        }
                    }

                    canvas.width = width;
                    canvas.height = height;

                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    canvas.toBlob(function(blob) {
                        const extensao = file.name.substring(file.name.lastIndexOf('.')).toLowerCase();
                        const novoNome = file.name.substring(0, file.name.lastIndexOf('.')) + '_min' + (extensao === '.webp' ? '.webp' : '.jpg');
                        const novoFormato = extensao === '.webp' ? 'image/webp' : 'image/jpeg';
                        
                        const compressedFile = new File([blob], novoNome, {
                            type: novoFormato,
                            lastModified: Date.now()
                        });

                        try {
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(compressedFile);
                            input.files = dataTransfer.files;
                        } catch (err) {
                            console.error("Erro no DataTransfer do Certificado:", err);
                        }

                        const blobURL = URL.createObjectURL(blob);
                        $('#certPreview').attr('src', blobURL);
                        $('#certPreview').fadeIn();
                    }, file.type === 'image/webp' ? 'image/webp' : 'image/jpeg', 0.75);
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>
