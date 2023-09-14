<?php
    include_once("menu.php");
    $usuario = $_SESSION['usuario'];
    if(isset($_SESSION['usuario'])){
        include_once("classes/conexao.php");
        $c = new conectar();
        $conexao = $c->conexao();
?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Area Administrativa</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Area Administrativa</li>
                        </ol>
                        <div class="row">
                        </div>    
                    </div>
                </main>
            
<?php 
    include_once("rodape.php");
}else{
    echo "<script language='javascript'>window.alert('ERRO - não está Logado'); </script>";
    echo "<script language='javascript'>window.location='login.php'; </script>";
}