<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\CampeonatoModel;
use App\Models\CategoriaModel;
use App\Models\ClassificacaoModel;
use App\Models\LutaModel;
use App\Models\SumulaKataModel;
use App\Models\SumulaTameshiwariModel;
use App\Models\SumulaKumiteModel;
use App\Models\AuditoriaModel;

/**
 * Controlador de Homologação de Resultados e Classificação Oficial
 */
class HomologacaoController extends Controller
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
        $tabelaMedalhas = ClassificacaoModel::getMedalTable($id);

        // Agrupa pódios por categoria
        $podiosPorCategoria = [];
        foreach ($categorias as $cat) {
            $podiosPorCategoria[$cat['id_categoria']] = ClassificacaoModel::byCategoria((int)$cat['id_categoria']);
        }

        $this->render('resultados/index', [
            'title' => 'Resultados Oficiais | ' . $campeonato['titulo'],
            'campeonato' => $campeonato,
            'categorias' => $categorias,
            'podiosPorCategoria' => $podiosPorCategoria,
            'tabelaMedalhas' => $tabelaMedalhas
        ]);
    }

    public function homologate(string $id, string $idCategoria): void
    {
        $this->requireAdmin();
        $this->validateCsrf();
        $idCampeonato = (int)$id;
        $idCategoria = (int)$idCategoria;

        $categoria = CategoriaModel::find($idCategoria);
        if (!$categoria) {
            Session::flash('error', 'Categoria não encontrada.');
            $this->redirect("/campeonatos/{$idCampeonato}/resultados");
        }

        $modalidade = $categoria['modalidade'];

        if ($modalidade === 'kumite') {
            // No Kumite, pega a luta final (última rodada)
            $chave = \App\Models\ChaveModel::byCategoria($idCategoria);
            if (!$chave) {
                Session::flash('error', 'Chaveamento inexistente.');
                $this->redirect("/campeonatos/{$idCampeonato}/resultados");
            }
            $lutas = LutaModel::byChave((int)$chave['id_chave']);
            $finalMatch = end($lutas);

            if ($finalMatch && $finalMatch['id_vencedor']) {
                $campeaoId = (int)$finalMatch['id_vencedor'];
                $viceId = ($campeaoId == $finalMatch['id_inscricao_aka']) ? (int)$finalMatch['id_inscricao_shiro'] : (int)$finalMatch['id_inscricao_aka'];

                ClassificacaoModel::savePodium($idCampeonato, $idCategoria, $campeaoId, 1, 'ouro', 10);
                if ($viceId > 0) {
                    ClassificacaoModel::savePodium($idCampeonato, $idCategoria, $viceId, 2, 'prata', 6);
                }
            }
        } elseif ($modalidade === 'kata') {
            $ranking = SumulaKataModel::getRanking($idCategoria, 1);
            if (!empty($ranking[0])) {
                ClassificacaoModel::savePodium($idCampeonato, $idCategoria, (int)$ranking[0]['id_inscricao'], 1, 'ouro', 10);
            }
            if (!empty($ranking[1])) {
                ClassificacaoModel::savePodium($idCampeonato, $idCategoria, (int)$ranking[1]['id_inscricao'], 2, 'prata', 6);
            }
            if (!empty($ranking[2])) {
                ClassificacaoModel::savePodium($idCampeonato, $idCategoria, (int)$ranking[2]['id_inscricao'], 3, 'bronze', 3);
            }
        } elseif ($modalidade === 'tameshiwari') {
            $ranking = SumulaTameshiwariModel::getRanking($idCategoria);
            if (!empty($ranking[0])) {
                ClassificacaoModel::savePodium($idCampeonato, $idCategoria, (int)$ranking[0]['id_inscricao'], 1, 'ouro', 10);
            }
            if (!empty($ranking[1])) {
                ClassificacaoModel::savePodium($idCampeonato, $idCategoria, (int)$ranking[1]['id_inscricao'], 2, 'prata', 6);
            }
            if (!empty($ranking[2])) {
                ClassificacaoModel::savePodium($idCampeonato, $idCategoria, (int)$ranking[2]['id_inscricao'], 3, 'bronze', 3);
            }
        }

        AuditoriaModel::log('HOMOLOGAR_CATEGORIA', 'campeonato_categorias', $idCategoria, null, [
            'modalidade' => $modalidade
        ], 'Homologação oficial de resultados', $idCampeonato);

        Session::flash('success', "Resultados da categoria '{$categoria['nome']}' homologados oficialmente com sucesso!");
        $this->redirect("/campeonatos/{$idCampeonato}/resultados");
    }

    public function rectify(string $id, string $idSumula): void
    {
        $this->requireAdmin();
        $this->validateCsrf();
        $idCampeonato = (int)$id;
        $idSumula = (int)$idSumula;

        $justificativa = trim($this->post('justificativa', ''));
        if (empty($justificativa)) {
            Session::flash('error', 'A retificação de um resultado homologado exige justificativa formal obrigatória.');
            $this->redirect("/campeonatos/{$idCampeonato}/resultados");
        }

        $sumula = SumulaKumiteModel::byLuta($idSumula);
        AuditoriaModel::log('RETIFICAR_SUMULA', 'campeonato_sumulas_kumite', $idSumula, $sumula, null, $justificativa, $idCampeonato);

        Session::flash('success', 'Procedimento de retificação registrado na auditoria oficial.');
        $this->redirect("/campeonatos/{$idCampeonato}/resultados");
    }
}
