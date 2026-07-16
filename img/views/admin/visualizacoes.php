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
              $contador_json = "contador.json";
              if (file_exists($contador_json)) {
                  $data = json_decode(file_get_contents($contador_json), true);
                  $contador = 1;
                  foreach ($data as $url => $info) {
                      $caminho_amigavel = str_replace("site_kenshydokan_php_puro/", "", $url);
                      $data_ultimo = isset($info['data']) ? date_format(date_create($info['data']), "d/m/Y H:i:s") : 'N/A';
                      ?>
                      <tr>
                        <td class="align-middle font-weight-bold text-secondary"><?php echo $contador; ?></td>
                        <td class="align-middle text-dark font-weight-bold small"><?php echo htmlspecialchars($caminho_amigavel); ?></td>
                        <td class="align-middle text-muted small"><?php echo htmlspecialchars($data_ultimo); ?></td>
                        <td class="align-middle text-center">
                          <span class="badge badge-danger font-weight-bold px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.85rem;">
                            <?php echo number_format($info['contagem'], 0, ',', '.'); ?>
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