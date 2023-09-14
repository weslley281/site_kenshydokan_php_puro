<!-- Navigation -->
  <?php include("menu.php"); ?>
<!-- /Navigation -->

<!-- This snippet uses Font Awesome 5 Free as a dependency. You can download it at fontawesome.io! -->

<section class="pricing py-5">
  <div class="container text-center">
  	<h3> Valores das Filiações</h3>
    <div class="row">
      <!-- Free Tier -->
      <div class="col-lg-4">
        <div class="card mb-5 mb-lg-0">
          <div class="card-body">
            <h5 class="card-title text-muted text-uppercase text-center">Atleta</h5>
            <h6 class="card-price text-center">R$50<span class="period">/Anual</span></h6>
            <hr>
            <ul class="fa-ul">
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Registrar-se em qualquer academia filiado</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Registrar-se em palestras</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Registrar-se em curso</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Registrar-se em campeonatos</li>
              <li class="text-muted"><span class="fa-li"><i class="fas fa-times"></i></span>Dar aulas</li>
              <li class="text-muted"><span class="fa-li"><i class="fas fa-times"></i></span>Enviar alunos para campeonatos</li>
              <li class="text-muted"><span class="fa-li"><i class="fas fa-times"></i></span>Enviar alunos para exame de graduação</li>
            </ul>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#filiar">
              Filiar
            </button>
          </div>
        </div>
      </div>
      <!-- Plus Tier -->
      <div class="col-lg-4">
        <div class="card mb-5 mb-lg-0">
          <div class="card-body">
            <h5 class="card-title text-muted text-uppercase text-center">Professor</h5>
            <h6 class="card-price text-center">R$80,00<span class="period">/anual</span></h6>
            <hr>
            <ul class="fa-ul">
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Registrar-se em qualquer academia filiado</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Registrar-se em palestras</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Registrar-se em curso</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Registrar-se em campeonatos</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Dar aulas</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Enviar alunos para campeonatos</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Enviar alunos para exame de graduação</li>
            </ul>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#filiar">
              Filiar
            </button>
          </div>
        </div>
      </div>
      <!-- Pro Tier -->
      <div class="col-lg-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title text-muted text-uppercase text-center">Academia</h5>
            <h6 class="card-price text-center">R$200,00<span class="period">/anual</span></h6>
            <hr>
            <ul class="fa-ul">
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Registrar-se em campeonatos</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Dar aulas de Karatê Kenshydokan</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Enviar alunos para campeonatos</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Enviar alunos para exame de graduação</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>Realizar eventos mediante a nossa altorização</li>
              <li><span class="fa-li"><i class="fas fa-check"></i></span>E muito Mais</li>
            </ul>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#filiar">
              Filiar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
  <?php 
  	include("rodape.php");
  ?>
<!-- /Footer -->

<!-- Modal -->
<div class="modal fade" id="filiar" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="TituloModalCentralizado">Dados para Cadastro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="funcoes/filiar.php" method="post">
          <!-- nome -->
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" id="inputGroup-sizing-default">Nome</span>
            </div>
            <input type="text" class="form-control" aria-label="Exemplo do tamanho do input" aria-describedby="inputGroup-sizing-default" name="nome">
          </div>

          <!-- dojo -->
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" id="inputGroup-sizing-default">Dojo</span>
            </div>
            <input type="text" class="form-control" aria-label="Exemplo do tamanho do input" aria-describedby="inputGroup-sizing-default" name="dojo">
          </div>

          <!-- graduacao -->
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" id="inputGroup-sizing-default">Graduação</span>
            </div>
            <select class="form-control" id="graduacao" name="graduacao">
              <option>faixa colorida 7° Kyu</option>
              <option>faixa colorida 6° Kyu</option>
              <option>faixa colorida 5° Kyu</option>
              <option>faixa colorida 4° Kyu</option>
              <option>faixa colorida 3° Kyu</option>
              <option>faixa colorida 2° Kyu</option>
              <option>faixa colorida 1° Kyu</option>
              <option>faixa preta 1° Dan</option>
              <option>faixa preta 2° Dan</option>
              <option>faixa preta 3° Dan</option>
              <option>faixa preta 4° Dan</option>
              <option>faixa preta 5° Dan</option>
            </select>
          </div>

          <!-- telefone -->
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" id="inputGroup-sizing-default">Telefone</span>
            </div>
            <input type="text" class="form-control" aria-label="Exemplo do tamanho do input" aria-describedby="inputGroup-sizing-default" name="telefone">
          </div>

          <!-- rg -->
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" id="inputGroup-sizing-default">RG</span>
            </div>
            <input type="text" class="form-control" aria-label="Exemplo do tamanho do input" aria-describedby="inputGroup-sizing-default" name="rg">
          </div>

          <!-- email -->
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" id="inputGroup-sizing-default">Email</span>
            </div>
            <input type="email" class="form-control" aria-label="Exemplo do tamanho do input" aria-describedby="inputGroup-sizing-default" name="email">
          </div>

          <!-- endereco -->
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" id="inputGroup-sizing-default">Endereço</span>
            </div>
            <input type="text" class="form-control" aria-label="Exemplo do tamanho do input" aria-describedby="inputGroup-sizing-default" name="endereco">
          </div>

          <!-- cidade -->
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" id="inputGroup-sizing-default">Cidade</span>
            </div>
            <input type="text" class="form-control" aria-label="Exemplo do tamanho do input" aria-describedby="inputGroup-sizing-default" name="cidade">
          </div>

          <!-- estado -->
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" id="inputGroup-sizing-default">Estado</span>
            </div>
            <select class="form-control" id="exampleFormControlSelect1" name="estado">
              <option>Acre</option>
              <option>Alagoas</option>
              <option>Amapá</option>
              <option>Amazonas</option>
              <option>Bahia</option>
              <option>Ceará</option>
              <option>Distrito Federal</option>
              <option>Espírito Santo</option>
              <option>Goiás</option>
              <option>Maranhão</option>
              <option>Mato Grosso</option>
              <option>Mato Grosso do Sul</option>
              <option>Minas Gerais</option>
              <option>Pará</option>
              <option>Paraíba</option>
              <option>Paraná</option>
              <option>Pernambuco</option>
              <option>Piauí</option>
              <option>Rio de Janeiro</option>
              <option>Rio Grande do Norte</option>
              <option>Rio Grande do Sul</option>
              <option>Rondônia</option>
              <option>Roraima</option>
              <option>Santa Catarina</option>
              <option>São Paulo</option>
              <option>Sergipe</option>
              <option>Tocantins</option>
            </select>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <input class="btn btn-primary" type="submit" name="enviar" value="enviar">
        </form>
      </div>
    </div>
  </div>
</div>