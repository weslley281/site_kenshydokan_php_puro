<?php
// views/gerenciamento_dojo/financeiro.php
$movimentacoes = $financeiroModel->buscarMovimentacoesMes($referencia);
?>

<div class="card border-0 shadow-sm rounded-lg mb-4">
  <div class="card-body p-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
      <div>
        <h5 class="text-danger font-weight-bold mb-1"><i class="fa-solid fa-cash-register mr-2"></i>Livro Caixa - Fluxo Financeiro</h5>
        <p class="text-muted small mb-0">Listagem de entradas e saídas de <strong><?php echo date("m/Y", strtotime($referencia . '-01')); ?></strong>.</p>
      </div>
      <div class="mt-2 mt-md-0">
        <!-- Botão para Lançar Movimentação -->
        <button type="button" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-4 py-2" data-toggle="modal" data-target="#modalLancarFinanceiro">
          <i class="fa-solid fa-plus-circle mr-2"></i> Nova Movimentação
        </button>
      </div>
    </div>

    

    <!-- Tabela de Movimentações -->
    <div class="table-responsive">
      <table id="minhaTabela2" class="table table-hover align-middle" width="100%" cellspacing="0">
        <thead>
          <tr class="text-secondary small font-weight-bold border-bottom">
            <th scope="col">Data</th>
            <th scope="col">Descrição</th>
            <th scope="col">Categoria</th>
            <th scope="col">Tipo</th>
            <th scope="col" class="text-right">Valor</th>
            <th scope="col" class="text-center" style="width: 100px;">Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($movimentacoes)): ?>
            <?php foreach ($movimentacoes as $m): 
              $id_financeiro = $m['id'];
              $tipo_mov = strtolower($m['tipo']);
              
              // Resolve badges
              $badge_class = 'secondary';
              $label_tipo = 'Desconhecido';
              $text_color = 'text-dark';
              if ($tipo_mov == 'entrada') {
                $badge_class = 'success';
                $label_tipo = 'Entrada';
                $text_color = 'text-success';
              } elseif ($tipo_mov == 'saida') {
                $badge_class = 'danger';
                $label_tipo = 'Saída';
                $text_color = 'text-danger';
              }

              // Tradução de categoria
              $cat_label = 'Outros';
              if ($m['categoria'] == 'mensalidade') $cat_label = 'Mensalidade';
              elseif ($m['categoria'] == 'aluguel') $cat_label = 'Aluguel';
              elseif ($m['categoria'] == 'luz') $cat_label = 'Água/Luz/Net';
              elseif ($m['categoria'] == 'equipamentos') $cat_label = 'Equipamentos';
            ?>
              <tr>
                <td class="align-middle text-dark small"><?php echo date("d/m/Y", strtotime($m['data_movimentacao'])); ?></td>
                <td class="align-middle font-weight-bold text-dark text-capitalize">
                  <?php echo htmlspecialchars($m['descricao'] ?? ''); ?>
                </td>
                <td class="align-middle text-secondary small font-weight-bold text-uppercase"><?php echo $cat_label; ?></td>
                <td class="align-middle">
                  <span class="badge badge-<?php echo $badge_class; ?> text-uppercase px-2 py-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                    <?php echo $label_tipo; ?>
                  </span>
                </td>
                <td class="align-middle font-weight-bold <?php echo $text_color; ?> text-right">
                  <?php echo $tipo_mov == 'saida' ? '- ' : '+ '; ?>R$ <?php echo number_format($m['valor'], 2, ',', '.'); ?>
                </td>
                <td class="align-middle text-center">
                  <!-- Apenas permite excluir lançamentos que não sejam automáticos de mensalidades para evitar inconsistência (ou permite sob aviso) -->
                  <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle" data-toggle="modal" data-target="#modalExcluir<?php echo $id_financeiro; ?>" title="Remover Lançamento" style="width: 32px; height: 32px; padding: 5px 0;">
                    <i class="fa-solid fa-trash"></i>
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
