<style>
    .form-signin {
        width: 100%;
        max-width: 330px;
        padding: 15px;
        margin: 0 auto;
    }
</style>0
<div class="container mt-5">
    <h1>Validar entrada de aluno</h1>
    <form class="form-signin" action="./controllers/ExpirationController.php?action=validate" method="post">
        <h1 class="h3 mb-3 font-weight-normal mt-5">Por favor valide a entrada do aluno:</h1>
        <label for="query" class="sr-only">Id ou email do Aluno</label>
        <input type="email" id="inputEmail" class="form-control" required autofocus>

        <button class="btn btn-lg btn-primary btn-block mt-5" type="submit">Validar</button>
    </form>
</div>