<div class="tab-pane fade show active">
    <div class="container text-center">
        <h2>Todos os Usuários</h2>

        <table id="minhaTabela" class="table table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Nome da Página</th>
                    <th>Contagem</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $contador_json = "contador.json";
                if (file_exists($contador_json)) {
                    $data = json_decode(file_get_contents($contador_json), true);
                    $contador = 1;
                    foreach ($data as $url => $info) {
                        $nome = explode("/", $url);
                        echo "<tr>";
                        echo "<td>$contador</td>";
                        echo "<td>$nome[2] $url</td>";
                        echo "<td>{$info['contagem']}</td>";
                        echo "</tr>";
                        $contador++;
                    }
                }
                ?>
            </tbody>
        </table>

    </div>
</div>