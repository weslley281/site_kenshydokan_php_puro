<div class="container">
    <div class="row mt-5">
        <div class="col-12 col-sm-6 col-md-4 col-lg-4 my-2">
            <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="./images/aulas.jpg" alt="Imagem de capa do card">
                <div class="card-body">
                    <h5 class="card-title">Entrada de Alunos</h5>
                    <p class="card-text">Validar entrada do Aluno</p>
                    <a href="index.php?page=validate" class="btn btn-primary"><i class="fa-solid fa-eye"></i> Visitar</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-4 my-2">
            <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="./images/alunos.jpg" alt="Imagem de capa do card">
                <div class="card-body">
                    <h5 class="card-title">Alunos</h5>
                    <p class="card-text">Alunos ativos <strong><?= $user->countAllStudents() ?></strong>.</p>
                    <a href="index.php?page=users&query=students" class="btn btn-primary"><i class="fa-solid fa-eye"></i> Visitar</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-4 my-2">
            <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="./images/instrutores.jpg" alt="Imagem de capa do card">
                <div class="card-body">
                    <h5 class="card-title">Instrutores</h5>
                    <p class="card-text">Instrutores ativos <strong><?= $user->countAllInstructors() ?></strong>.</p>
                    <a href="index.php?page=users&query=instructors" class="btn btn-primary"><i class="fa-solid fa-eye"></i> Visitar</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-4 my-2">
            <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="./images/turmas.jpg" alt="Imagem de capa do card">
                <div class="card-body">
                    <h5 class="card-title">Turmas</h5>
                    <p class="card-text">Turmas ativas <strong><?= $modality->countAll() ?></strong>.</p>
                    <a href="index.php?page=modalities" class="btn btn-primary"><i class="fa-solid fa-eye"></i> Visitar</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-4 my-2">
            <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="./images/financeiro.jpg" alt="Imagem de capa do card">
                <div class="card-body">
                    <h5 class="card-title">Financeiro</h5>
                    <p class="card-text">Mensalidade Atrazada <strong>0</strong>.</p>
                    <a href="index.php?page=financial" class="btn btn-primary"><i class="fa-solid fa-eye"></i> Visitar</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-4 my-2">
            <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="./images/campeonatos.jpg" alt="Imagem de capa do card">
                <div class="card-body">
                    <h5 class="card-title">Campeonatos</h5>
                    <p class="card-text">Campeonatos pendente <strong>0</strong>.</p>
                    <a href="index.php?page=championships" class="btn btn-primary"><i class="fa-solid fa-eye"></i> Visitar</a>
                </div>
            </div>
        </div>
    </div>
</div>