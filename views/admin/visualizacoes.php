<div class="tab-pane fade show active">
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Estatísticas de Visualizações</h2>
    </div>

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
              include_once __DIR__ . "/../../db/conexao.php";
              $db = new Conexao();
              $conexao = $db->conectar();
              
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

                  $resultado = $conexao->query("SELECT caminho, COUNT(*) as contagem, MAX(data_acesso) as data_ultimo FROM visualizacoes_paginas GROUP BY caminho ORDER BY contagem DESC");
                  if ($resultado) {
                      while ($row = $resultado->fetch_assoc()) {
                          $visualizacoes[] = $row;
                      }
                  }
                  $conexao->close();
              }

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