                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Weslley henrique Vieira Ferraz 2020</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
<!-- Modal Cadastra Graduação-->
<div class="modal fade" id="ModalGraduacoes" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="TituloModalCentralizado">Cadastrar Graduações</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="funcoes/cadastrar_graduacao.php" method="post">
            <input class="form-control" type="text" placeholder="Nome da Graduação" name="graduacao">
            <button type="submit" class="btn btn-success">Salvar</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
    </body>
</html>