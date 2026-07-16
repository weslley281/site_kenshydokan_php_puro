<?php
$page_title = "Eventos e Seminários";
include __DIR__ . "/menu.php";
include __DIR__ . "/_perfil_auth.php";
include_once __DIR__ . "/../../models/eventoModel.php";

$eventoModelRepo = new Evento();
$eventos = $eventoModelRepo->buscarTodosEventos();
?>

<body>
    <div class="container mt-5">
        <div class="row">
            <?php include __DIR__ . "/_perfil_menu.php"; ?>

            <div class="col-lg-9">
                <div class="card border-0 shadow-sm rounded-lg mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-danger-light p-3 rounded-circle text-danger mr-3 d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: rgba(217, 35, 45, 0.1);">
                                <i class="fa-solid fa-calendar-days fa-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-weight-bold text-dark mb-0">Seminários e Eventos</h4>
                                <p class="text-muted mb-0 small">Acompanhe nossa agenda de atividades presenciais e transmissões online.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <?php if (empty($eventos)): ?>
                        <div class="col-12">
                            <div class="card border-0 shadow-sm rounded-lg py-5 text-center">
                                <div class="card-body">
                                    <i class="fa-regular fa-calendar-times text-muted fa-3x mb-3"></i>
                                    <h5 class="font-weight-bold text-dark mb-1">Nenhum evento agendado</h5>
                                    <p class="text-muted small">Fique atento! Novas datas de seminários e aulas online serão publicadas em breve.</p>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($eventos as $e): 
                            $titulo = $e["titulo"];
                            $descricao = $e["descricao"];
                            $data_obj = date_create($e["data_evento"]);
                            $dia = date_format($data_obj, "d");
                            $mes = date_format($data_obj, "M");
                            $hora = date_format($data_obj, "H:i");
                            $tipo = $e["tipo"];
                            $endereco = $e["endereco"];
                            $link = $e["link_assistir"];
                            
                            // Traduzindo meses abreviados para PT-BR
                            $meses = [
                                'Jan' => 'Jan', 'Feb' => 'Fev', 'Mar' => 'Mar', 'Apr' => 'Abr',
                                'May' => 'Mai', 'Jun' => 'Jun', 'Jul' => 'Jul', 'Aug' => 'Ago',
                                'Sep' => 'Set', 'Oct' => 'Out', 'Nov' => 'Nov', 'Dec' => 'Dez'
                            ];
                            $mes_pt = isset($meses[$mes]) ? $meses[$mes] : $mes;
                        ?>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 border-0 shadow-sm rounded-lg overflow-hidden transition-all hover-shadow-lg" style="transition: all 0.3s ease;">
                                    <div class="card-body p-4 d-flex flex-column">
                                        <!-- Top Info -->
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <!-- Date Badge -->
                                            <div class="text-center rounded-lg bg-light p-2 border-left border-danger" style="width: 60px; border-left-width: 4px !important;">
                                                <span class="d-block font-weight-bold text-dark h5 mb-0"><?php echo $dia; ?></span>
                                                <span class="d-block text-danger font-weight-bold small text-uppercase" style="font-size: 0.7rem;"><?php echo $mes_pt; ?></span>
                                            </div>
                                            <!-- Type Badge -->
                                            <span class="badge badge-<?php echo ($tipo == 'online') ? 'success' : 'primary'; ?> px-3 py-2 rounded-pill font-weight-bold text-uppercase" style="font-size: 0.7rem;">
                                                <i class="fa-solid <?php echo ($tipo == 'online') ? 'fa-video' : 'fa-map-location-dot'; ?> mr-1"></i>
                                                <?php echo $tipo; ?>
                                            </span>
                                        </div>

                                        <h5 class="card-title font-weight-bold text-dark mb-2 text-capitalize"><?php echo htmlspecialchars($titulo); ?></h5>
                                        <p class="text-muted small mb-3 flex-grow-1"><?php echo nl2br(htmlspecialchars($descricao)); ?></p>

                                        <div class="border-top pt-3 mt-auto">
                                            <div class="d-flex align-items-center text-secondary small mb-2">
                                                <i class="fa-solid fa-clock text-danger mr-2" style="width: 16px;"></i>
                                                <span>Horário: <strong><?php echo $hora; ?></strong></span>
                                            </div>

                                            <?php if ($tipo === 'presencial'): ?>
                                                <div class="d-flex align-items-start text-secondary small">
                                                    <i class="fa-solid fa-location-dot text-danger mr-2 mt-1" style="width: 16px;"></i>
                                                    <span>Endereço: <?php echo !empty($endereco) ? htmlspecialchars($endereco) : '<span class="text-muted">A definir</span>'; ?></span>
                                                </div>
                                            <?php else: ?>
                                                <div class="d-flex align-items-start text-secondary small mb-3">
                                                    <i class="fa-solid fa-circle-play text-success mr-2 mt-1" style="width: 16px;"></i>
                                                    <span>Modalidade: <strong>Webinário Online</strong></span>
                                                </div>
                                                <?php if (!empty($link)): ?>
                                                    <a href="<?php echo htmlspecialchars($link); ?>" target="_blank" class="btn btn-danger btn-block rounded-pill font-weight-bold shadow-sm py-2">
                                                        <i class="fa-solid fa-video mr-2"></i>Assistir Evento Online
                                                    </a>
                                                <?php else: ?>
                                                    <button class="btn btn-secondary btn-block rounded-pill font-weight-bold py-2" disabled>
                                                        <i class="fa-solid fa-video-slash mr-2"></i>Link Indisponível
                                                    </button>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>

<?php include __DIR__ . "/rodape.php"; ?>