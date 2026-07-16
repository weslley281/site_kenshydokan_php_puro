<?php
$pageTitle = "Campeonatos & Torneios de Karatê | Kenshydokan";
$pageDescription = "Acompanhe a agenda oficial de competições de karatê de contato. Inscrições abertas para torneios internos e externos.";
include "menu.php";
include_once "../models/campeonatoModel.php";

$campeonatoModel = new Campeonato();
$campeonatos = $campeonatoModel->buscarTodos();
?>

<!-- Banner Hero com Estilo Premium -->
<div class="bg-dark text-white py-5 mb-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, #1e1e24 0%, #a8201a 100%);">
    <div class="container text-center py-4">
        <span class="badge badge-danger px-3 py-2 rounded-pill font-weight-bold text-uppercase mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">
            <i class="fa-solid fa-trophy mr-1"></i> Competicoes
        </span>
        <h1 class="display-4 font-weight-bold mb-2">Campeonatos & Torneios</h1>
        <p class="lead text-white-50 max-width-600 mx-auto font-weight-light">Acompanhe a agenda de torneios oficiais do Instituto Kenshydokan, inscreva-se e participe.</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row">
        <?php if (!empty($campeonatos)): ?>
            <?php foreach ($campeonatos as $res): 
                $id_campeonato = $res["id_campeonato"];
                $titulo_camp = htmlspecialchars($res["titulo"]);
                $subtitulo = htmlspecialchars($res["subtitulo"]);
                $endereco = htmlspecialchars($res["endereco"]);
                $ativo_camp = $res["ativo"]; // "sim" ou "nao"
                $tipo = $res["tipo"]; // "interno" ou "externo"
                $link_externo = htmlspecialchars($res["link_externo"]);

                // Tratar data do campeonato
                $data_datetime = strtotime($res["dataCriacao"]);
                $dia = date("d", $data_datetime);
                $meses_pt = [
                    '01' => 'JAN', '02' => 'FEV', '03' => 'MAR', '04' => 'ABR',
                    '05' => 'MAI', '06' => 'JUN', '07' => 'JUL', '08' => 'AGO',
                    '09' => 'SET', '10' => 'OUT', '11' => 'NOV', '12' => 'DEZ'
                ];
                $mes_num = date("m", $data_datetime);
                $mes_abrev = isset($meses_pt[$mes_num]) ? $meses_pt[$mes_num] : date("M", $data_datetime);
                $ano = date("Y", $data_datetime);
            ?>
                <div class="col-lg-12 mb-4">
                    <div class="card border-0 shadow-sm rounded-lg overflow-hidden bg-white h-100 p-3 p-md-4">
                        <div class="row align-items-center">
                            <!-- Calendario Folhinha -->
                            <div class="col-md-2 col-sm-3 text-center mb-3 mb-md-0">
                                <div class="d-inline-block rounded-lg shadow-sm overflow-hidden bg-light" style="width: 100px;">
                                    <div class="bg-danger text-white py-1 font-weight-bold small text-uppercase" style="letter-spacing: 1px;">
                                        <?php echo $mes_abrev; ?>
                                    </div>
                                    <div class="py-3 bg-white">
                                        <span class="display-4 font-weight-bold text-dark d-block mb-0 leading-none" style="line-height: 1;"><?php echo $dia; ?></span>
                                        <small class="text-secondary font-weight-bold"><?php echo $ano; ?></small>
                                    </div>
                                </div>
                            </div>

                            <!-- Informacoes do Campeonato -->
                            <div class="col-md-7 col-sm-9 pl-md-4">
                                <div class="d-flex align-items-center flex-wrap mb-2" style="gap: 8px;">
                                    <!-- Badge Tipo -->
                                    <?php if ($tipo === 'externo'): ?>
                                        <span class="badge badge-info text-uppercase px-2 py-1 font-weight-bold" style="font-size: 0.65rem;">
                                            <i class="fa-solid fa-square-arrow-up-right mr-1"></i>Externo
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-success text-uppercase px-2 py-1 font-weight-bold" style="font-size: 0.65rem;">
                                            <i class="fa-solid fa-house-chimney mr-1"></i>Interno
                                        </span>
                                    <?php endif; ?>

                                    <!-- Badge Status -->
                                    <?php if ($ativo_camp === 'sim'): ?>
                                        <span class="badge badge-success px-2 py-1 font-weight-bold text-uppercase" style="font-size: 0.65rem;">
                                            Inscricoes Abertas
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary px-2 py-1 font-weight-bold text-uppercase" style="font-size: 0.65rem;">
                                            Realizado
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <h4 class="font-weight-bold text-dark mb-1"><?php echo $titulo_camp; ?></h4>
                                <p class="text-secondary font-weight-light mb-3"><?php echo $subtitulo; ?></p>
                                
                                <p class="text-muted small mb-0">
                                    <i class="fa-solid fa-location-dot text-danger mr-2"></i><?php echo $endereco; ?>
                                </p>
                            </div>

                            <!-- Botoes de Acao -->
                            <div class="col-md-3 text-md-right text-left mt-3 mt-md-0">
                                <?php if ($ativo_camp === 'sim'): ?>
                                    <?php if ($tipo === 'externo'): ?>
                                        <a href="<?php echo $link_externo; ?>" target="_blank" class="btn btn-danger font-weight-bold rounded-pill px-4 shadow-sm py-2">
                                            <i class="fa-solid fa-square-arrow-up-right mr-2"></i>Inscricao Externa
                                        </a>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-danger font-weight-bold rounded-pill px-4 shadow-sm py-2 btn-inscrever" data-id="<?php echo $id_campeonato; ?>" data-titulo="<?php echo $titulo_camp; ?>">
                                            <i class="fa-solid fa-user-plus mr-2"></i>Inscrever-se
                                        </button>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <button type="button" class="btn btn-outline-secondary font-weight-bold rounded-pill px-4 py-2" disabled>
                                        Inscricoes Encerradas
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="fa-solid fa-calendar-xmark text-secondary fa-3x mb-3" style="opacity: 0.5;"></i>
                <h5 class="text-secondary font-weight-bold">Nenhum campeonato agendado no momento.</h5>
                <p class="text-muted">Fique atento as atualizacoes da escola para novas competicoes!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal de Inscricao Campeonato Interno -->
<div class="modal fade" id="modalInscricao" tabindex="-1" role="dialog" aria-labelledby="modalInscricaoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-lg">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title font-weight-bold" id="modalInscricaoLabel">
                    <i class="fa-solid fa-file-signature mr-2"></i>Ficha de Inscricao
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formInscricao">
                <input type="hidden" name="id_campeonato" id="inscricao_id">
                <input type="hidden" name="titulo_campeonato" id="inscricao_titulo_camp">
                
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <small class="text-muted text-uppercase font-weight-bold">Campeonato Selecionado</small>
                        <h5 class="font-weight-bold text-dark" id="txt_campeonato_titulo"></h5>
                    </div>
                    
                    <hr>

                    <!-- Nome -->
                    <div class="form-group">
                        <label for="nome" class="text-secondary small font-weight-bold text-uppercase">Nome Completo *</label>
                        <input type="text" class="form-control bg-light border-0 shadow-sm" id="nome" name="nome" placeholder="Digite seu nome completo" required>
                    </div>

                    <!-- E-mail -->
                    <div class="form-group">
                        <label for="email" class="text-secondary small font-weight-bold text-uppercase">E-mail para Contato *</label>
                        <input type="email" class="form-control bg-light border-0 shadow-sm" id="email" name="email" placeholder="seu-email@exemplo.com" required>
                    </div>

                    <!-- Telefone -->
                    <div class="form-group">
                        <label for="telefone" class="text-secondary small font-weight-bold text-uppercase">Telefone / WhatsApp *</label>
                        <input type="text" class="form-control bg-light border-0 shadow-sm" id="telefone" name="telefone" placeholder="(65) 99999-9999" required>
                    </div>

                    <div class="row">
                        <!-- Dojo -->
                        <div class="col-md-6 form-group">
                            <label for="dojo" class="text-secondary small font-weight-bold text-uppercase">Dojo de Origem</label>
                            <input type="text" class="form-control bg-light border-0 shadow-sm" id="dojo" name="dojo" placeholder="Ex: Dojo Central">
                        </div>

                        <!-- Graduacao -->
                        <div class="col-md-6 form-group">
                            <label for="graduacao" class="text-secondary small font-weight-bold text-uppercase">Graduacao Atual</label>
                            <input type="text" class="form-control bg-light border-0 shadow-sm" id="graduacao" name="graduacao" placeholder="Ex: 3o Kyu (Verde)">
                        </div>
                    </div>

                    <!-- Observacoes -->
                    <div class="form-group">
                        <label for="observacoes" class="text-secondary small font-weight-bold text-uppercase">Categoria / Idade / Observacoes</label>
                        <textarea class="form-control bg-light border-0 shadow-sm" id="observacoes" name="observacoes" rows="3" placeholder="Insira sua categoria, peso ou observacoes adicionais..."></textarea>
                    </div>
                </div>
                
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary font-weight-bold rounded-pill px-4" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-danger font-weight-bold rounded-pill px-4 shadow-sm" id="btn_submit_inscricao">
                        <i class="fa-solid fa-paper-plane mr-2"></i>Enviar Inscricao
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Abrir modal de inscricao
    $(document).on("click", ".btn-inscrever", function() {
        var id = $(this).data("id");
        var titulo = $(this).data("titulo");
        
        $("#inscricao_id").val(id);
        $("#inscricao_titulo_camp").val(titulo);
        $("#txt_campeonato_titulo").text(titulo);
        
        // Limpar formulario anterior
        $("#formInscricao")[0].reset();
        
        $("#modalInscricao").modal("show");
    });

    // Enviar inscricao via AJAX
    var form = document.getElementById("formInscricao");
    form.addEventListener("submit", function(e) {
        e.preventDefault();
        
        var submitBtn = document.getElementById("btn_submit_inscricao");
        var originalBtnHtml = submitBtn.innerHTML;
        
        // Loading state
        submitBtn.setAttribute("disabled", "disabled");
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Enviando...';
        
        var formData = new FormData(form);
        
        fetch("../controllers/inscrever_campeonato.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.removeAttribute("disabled");
            submitBtn.innerHTML = originalBtnHtml;
            
            if (data.status === "success") {
                alert(data.message);
                $("#modalInscricao").modal("hide");
            } else {
                alert("Erro: " + data.message);
            }
        })
        .catch(error => {
            submitBtn.removeAttribute("disabled");
            submitBtn.innerHTML = originalBtnHtml;
            console.error("Erro na inscricao:", error);
            alert("Ocorreu um erro ao enviar sua inscricao. Tente novamente.");
        });
    });
});
</script>
<?php include "rodape.php"; ?>