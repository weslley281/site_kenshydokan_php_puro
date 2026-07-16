<?php
include_once __DIR__ . "/../../db/conexao.php";
$db = new Conexao();
$conexao = $db->conectar();

$diario = [];
$semanal = [];
$mensal = [];
$anual = [];
$visualizacoes = [];

if ($conexao) {
    // Garante que a tabela existe
    $conexao->query("CREATE TABLE IF NOT EXISTS visualizacoes_paginas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        caminho VARCHAR(255) NOT NULL,
        data_acesso DATETIME NOT NULL,
        INDEX (caminho),
        INDEX (data_acesso)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // 1. Diario (últimos 30 dias)
    $res = $conexao->query("
        SELECT DATE_FORMAT(data_acesso, '%d/%m') as rotulo, COUNT(*) as total 
        FROM visualizacoes_paginas 
        WHERE data_acesso >= DATE_SUB(NOW(), INTERVAL 30 DAY) 
        GROUP BY DATE(data_acesso) 
        ORDER BY DATE(data_acesso) ASC
    ");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $diario[] = $row;
        }
    }

    // 2. Semanal (últimas 12 semanas)
    $res = $conexao->query("
        SELECT YEARWEEK(data_acesso, 1) as semana_ano, COUNT(*) as total 
        FROM visualizacoes_paginas 
        WHERE data_acesso >= DATE_SUB(NOW(), INTERVAL 12 WEEK) 
        GROUP BY semana_ano 
        ORDER BY semana_ano ASC
    ");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $ano = substr($row['semana_ano'], 0, 4);
            $semana = substr($row['semana_ano'], 4, 2);
            $row['rotulo'] = "Sem " . $semana . "/" . $ano;
            $semanal[] = $row;
        }
    }

    // 3. Mensal (últimos 12 meses)
    $res = $conexao->query("
        SELECT DATE_FORMAT(data_acesso, '%m/%Y') as rotulo, COUNT(*) as total 
        FROM visualizacoes_paginas 
        WHERE data_acesso >= DATE_SUB(NOW(), INTERVAL 12 MONTH) 
        GROUP BY DATE_FORMAT(data_acesso, '%Y-%m') 
        ORDER BY DATE_FORMAT(data_acesso, '%Y-%m') ASC
    ");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $mensal[] = $row;
        }
    }

    // 4. Anual (histórico total)
    $res = $conexao->query("
        SELECT DATE_FORMAT(data_acesso, '%Y') as rotulo, COUNT(*) as total 
        FROM visualizacoes_paginas 
        GROUP BY YEAR(data_acesso) 
        ORDER BY YEAR(data_acesso) ASC
    ");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $anual[] = $row;
        }
    }

    // 5. Tabela geral (caminhos mais acessados)
    $resultado = $conexao->query("SELECT caminho, COUNT(*) as contagem, MAX(data_acesso) as data_ultimo FROM visualizacoes_paginas GROUP BY caminho ORDER BY contagem DESC");
    if ($resultado) {
        while ($row = $resultado->fetch_assoc()) {
            $visualizacoes[] = $row;
        }
    }
    $conexao->close();
}
?>

<div class="tab-pane fade show active">
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Estatísticas de Visualizações</h2>
    </div>

    <!-- Card do Gráfico Interativo com Chart.js -->
    <div class="card border-0 shadow-sm rounded-lg mb-4">
      <div class="card-body p-4">
        <div class="d-md-flex align-items-center justify-content-between mb-4">
          <h5 class="font-weight-bold text-dark mb-2 mb-md-0">
            <i class="fa-solid fa-chart-line text-danger mr-2"></i>Histórico de Acessos
          </h5>
          <div class="btn-group shadow-sm" id="filtroGrafico" role="group">
            <button type="button" class="btn btn-danger btn-sm px-3 active font-weight-bold" onclick="mudarPeriodo('diario', this)">Diário</button>
            <button type="button" class="btn btn-outline-danger btn-sm px-3 font-weight-bold" onclick="mudarPeriodo('semanal', this)">Semanal</button>
            <button type="button" class="btn btn-outline-danger btn-sm px-3 font-weight-bold" onclick="mudarPeriodo('mensal', this)">Mensal</button>
            <button type="button" class="btn btn-outline-danger btn-sm px-3 font-weight-bold" onclick="mudarPeriodo('anual', this)">Anual</button>
          </div>
        </div>
        <div style="position: relative; height: 320px; width: 100%;">
          <canvas id="chartVisualizacoes"></canvas>
        </div>
      </div>
    </div>

    <!-- Tabela Geral de URLs -->
    <div class="card border-0 shadow-sm rounded-lg">
      <div class="card-body p-4">
        <div class="table-responsive">
          <table id="minhaTabela" class="table table-hover align-middle" width="100%" cellspacing="0">
            <thead>
              <tr class="text-secondary small font-weight-bold border-bottom">
                <th scope="col" style="width: 70px;">N°</th>
                <th scope="col">Caminho / URL da Página</th>
                <th scope="col">Último Acesso</th>
                <th scope="col" class="text-center" style="width: 150px;">Total de Visualizações</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if (!empty($visualizacoes)) {
                  $contador = 1;
                  foreach ($visualizacoes as $v) {
                      $caminho_amigavel = str_replace("site_kenshydokan_php_puro/", "", $v['caminho']);
                      $data_ultimo = !empty($v['data_ultimo']) ? date_format(date_create($v['data_ultimo']), "d/m/Y H:i:s") : 'N/A';
                      ?>
                      <tr>
                        <td class="align-middle font-weight-bold text-secondary"><?php echo $contador; ?></td>
                        <td class="align-middle text-dark font-weight-bold small"><?php echo htmlspecialchars($caminho_amigavel); ?></td>
                        <td class="align-middle text-muted small"><?php echo htmlspecialchars($data_ultimo); ?></td>
                        <td class="align-middle text-center">
                          <span class="badge badge-danger font-weight-bold px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.85rem;">
                            <?php echo number_format($v['contagem'], 0, ',', '.'); ?>
                          </span>
                        </td>
                      </tr>
                      <?php
                      $contador++;
                  }
              } else {
                  echo '<tr><td colspan="4" class="text-center text-muted py-4">Nenhum dado de visualização registrado.</td></tr>';
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Scripts de Gráficos Locais -->
<script src="../../libs/chartjs/chart.umd.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Carrega os conjuntos de dados estruturados do PHP
    const dados = {
        diario: <?php echo json_encode($diario); ?>,
        semanal: <?php echo json_encode($semanal); ?>,
        mensal: <?php echo json_encode($mensal); ?>,
        anual: <?php echo json_encode($anual); ?>
    };

    // Função auxiliar para estruturar datasets
    function obterDataset(periodo) {
        const itens = dados[periodo] || [];
        return {
            labels: itens.map(item => item.rotulo),
            data: itens.map(item => parseInt(item.total))
        };
    }

    // Inicialização do Gráfico com o período Diário
    const datasetInicial = obterDataset('diario');
    const ctx = document.getElementById('chartVisualizacoes').getContext('2d');
    
    // Gradiente premium vermelho Kenshydokan sob a curva
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(217, 35, 45, 0.35)');
    gradient.addColorStop(1, 'rgba(217, 35, 45, 0.0)');

    const chartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: datasetInicial.labels,
            datasets: [{
                label: 'Visualizações',
                data: datasetInicial.data,
                borderColor: '#d9232d',
                backgroundColor: gradient,
                borderWidth: 2.5,
                fill: true,
                tension: 0.35, // Suaviza a linha (curva spline)
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#d9232d',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false // Oculta a legenda simples
                },
                tooltip: {
                    backgroundColor: '#1a1a1a',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    padding: 10,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return ' ' + context.parsed.y + ' visualizações';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#6c757d',
                        font: {
                            size: 9.5,
                            family: 'Arial'
                        }
                    }
                },
                y: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        color: '#6c757d',
                        font: {
                            size: 9.5,
                            family: 'Arial'
                        },
                        stepSize: 1,
                        beginAtZero: true
                    }
                }
            }
        }
    });

    // Função de filtro para troca de períodos dinâmica
    window.mudarPeriodo = function(periodo, botao) {
        // Altera estilização ativa do grupo de botões
        const botoes = document.querySelectorAll('#filtroGrafico button');
        botoes.forEach(b => {
            b.classList.remove('btn-danger', 'active');
            b.classList.add('btn-outline-danger');
        });
        botao.classList.remove('btn-outline-danger');
        botao.classList.add('btn-danger', 'active');

        // Substitui os rótulos e séries numéricas do gráfico e executa o update
        const novosDados = obterDataset(periodo);
        chartInstance.data.labels = novosDados.labels;
        chartInstance.data.datasets[0].data = novosDados.data;
        chartInstance.update();
    };
});
</script>