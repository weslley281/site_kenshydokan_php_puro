<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\CampeonatoModel;
use App\Models\CategoriaModel;
use App\Models\InscricaoModel;
use App\Models\ChaveModel;
use App\Models\LutaModel;
use App\Services\ChaveamentoService;
use App\Services\PdfService;
use App\Models\AuditoriaModel;

/**
 * Controlador de Sorteio e Visualização de Chaves
 */
class ChaveamentoController extends Controller
{
    public function index(string $id): void
    {
        $this->requireAuth();
        $id = (int)$id;
        $campeonato = CampeonatoModel::find($id);

        if (!$campeonato) {
            Session::flash('error', 'Campeonato não encontrado.');
            $this->redirect('/campeonatos');
        }

        $categorias = CategoriaModel::byCampeonato($id);

        $this->render('chaveamento/index', [
            'title' => 'Chaveamento | ' . $campeonato['titulo'],
            'campeonato' => $campeonato,
            'categorias' => $categorias
        ]);
    }

    public function generateBracket(string $idCategoria): void
    {
        $this->requireAdmin();
        $this->validateCsrf();
        $idCategoria = (int)$idCategoria;

        $categoria = CategoriaModel::find($idCategoria);
        if (!$categoria) {
            Session::flash('error', 'Categoria não encontrada.');
            $this->redirect('/campeonatos');
        }

        $idCampeonato = (int)$categoria['id_campeonato'];

        // Filtra apenas atletas APROVADOS (na pesagem e elegibilidade)
        $todasInscricoes = InscricaoModel::byCampeonato($idCampeonato, $idCategoria);
        $atletasAptos = array_filter($todasInscricoes, fn($i) => $i['status_elegibilidade'] === 'aprovada');
        $atletasAptos = array_values($atletasAptos);

        if (count($atletasAptos) < 2) {
            Session::flash('error', 'São necessários no mínimo 2 atletas aprovados na categoria para realizar o sorteio.');
            $this->redirect("/chaves/{$idCategoria}/visualizar");
        }

        try {
            // Executa algoritmo de sorteio eliminatório
            $resultado = ChaveamentoService::generateBracket($atletasAptos);

            // Remove chave antiga se houver
            ChaveModel::deleteByCategoria($idCategoria);

            // Salva registro da Chave
            $idChave = ChaveModel::saveBracket(
                $idCampeonato,
                $idCategoria,
                'eliminatoria_simples',
                $resultado['total_atletas'],
                $resultado['total_rodadas'],
                json_encode($resultado['arvore'], JSON_UNESCAPED_UNICODE)
            );

            // Salva as lutas geradas
            $lutaIdsMap = [];
            foreach ($resultado['lista_lutas'] as $luta) {
                $idLuta = LutaModel::create([
                    'id_chave' => $idChave,
                    'id_campeonato' => $idCampeonato,
                    'id_categoria' => $idCategoria,
                    'id_tatame' => null,
                    'numero_luta' => $luta['numero_luta'],
                    'rodada' => $luta['rodada'],
                    'posicao_na_chave' => $luta['posicao_na_chave'],
                    'id_inscricao_aka' => $luta['id_inscricao_aka'],
                    'id_inscricao_shiro' => $luta['id_inscricao_shiro'],
                    'status' => $luta['status'],
                    'proxima_luta_id' => null
                ]);

                // Se teve vencedor por Bye, registra o avanço
                if ($luta['id_vencedor']) {
                    LutaModel::setWinner($idLuta, $luta['id_vencedor'], 'wo');
                }

                $lutaIdsMap[$luta['rodada'] . '_' . $luta['posicao_na_chave']] = $idLuta;
            }

            // Atualiza os ponteiros de proxima_luta_id
            $conn = \App\Core\Database::getConnection();
            foreach ($resultado['lista_lutas'] as $luta) {
                if ($luta['rodada'] < $resultado['total_rodadas']) {
                    $proxKey = ($luta['rodada'] + 1) . '_' . $luta['proxima_posicao'];
                    if (isset($lutaIdsMap[$proxKey])) {
                        $currentId = $lutaIdsMap[$luta['rodada'] . '_' . $luta['posicao_na_chave']];
                        $proxId = $lutaIdsMap[$proxKey];
                        $stmtU = $conn->prepare("UPDATE campeonato_lutas SET proxima_luta_id = ? WHERE id_luta = ?");
                        $stmtU->bind_param("ii", $proxId, $currentId);
                        $stmtU->execute();
                        $stmtU->close();
                    }
                }
            }

            AuditoriaModel::log('SORTEIO_CHAVE', 'campeonato_chaves', $idChave, null, [
                'total_atletas' => $resultado['total_atletas'],
                'byes' => $resultado['total_byes']
            ], null, $idCampeonato);

            Session::flash('success', "Chaveamento gerado com sucesso! ({$resultado['total_atletas']} atletas, {$resultado['total_byes']} Byes).");
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao gerar chaveamento: ' . $e->getMessage());
        }

        $this->redirect("/chaves/{$idCategoria}/visualizar");
    }

    public function viewBracket(string $idCategoria): void
    {
        $this->requireAuth();
        $idCategoria = (int)$idCategoria;

        $categoria = CategoriaModel::find($idCategoria);
        if (!$categoria) {
            Session::flash('error', 'Categoria não encontrada.');
            $this->redirect('/campeonatos');
        }

        $campeonato = CampeonatoModel::find((int)$categoria['id_campeonato']);
        $chave = ChaveModel::byCategoria($idCategoria);
        $lutas = $chave ? LutaModel::byChave((int)$chave['id_chave']) : [];
        $inscricoes = InscricaoModel::byCampeonato((int)$campeonato['id_campeonato'], $idCategoria);

        // Agrupa lutas por rodada
        $lutasPorRodada = [];
        foreach ($lutas as $l) {
            $lutasPorRodada[$l['rodada']][] = $l;
        }

        $this->render('chaveamento/view', [
            'title' => 'Chave: ' . $categoria['nome'] . ' | ' . $campeonato['titulo'],
            'campeonato' => $campeonato,
            'categoria' => $categoria,
            'chave' => $chave,
            'lutasPorRodada' => $lutasPorRodada,
            'inscricoes' => $inscricoes
        ]);
    }

    public function exportPdf(string $idCategoria): void
    {
        $this->requireAuth();
        $idCategoria = (int)$idCategoria;

        $categoria = CategoriaModel::find($idCategoria);
        $campeonato = CampeonatoModel::find((int)$categoria['id_campeonato']);
        $chave = ChaveModel::byCategoria($idCategoria);

        if (!$chave) {
            Session::flash('error', 'Chaveamento ainda não foi gerado para esta categoria.');
            $this->redirect("/chaves/{$idCategoria}/visualizar");
        }

        $lutas = LutaModel::byChave((int)$chave['id_chave']);
        $pdfOutput = PdfService::generateBracketPdf($categoria, $lutas, $campeonato['titulo']);

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="chave_' . $idCategoria . '.pdf"');
        echo $pdfOutput;
        exit;
    }
}
