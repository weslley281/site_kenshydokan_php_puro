<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\CampeonatoModel;
use App\Models\CategoriaModel;
use App\Models\InscricaoModel;
use App\Models\PesagemModel;
use App\Services\PesagemService;
use App\Models\AuditoriaModel;

/**
 * Controlador da Balança e Pesagem Oficial
 */
class PesagemController extends Controller
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

        $inscricoes = InscricaoModel::byCampeonato($id);
        $historicoPesagens = PesagemModel::byCampeonato($id);
        $categorias = CategoriaModel::byCampeonato($id);

        $this->render('pesagem/index', [
            'title' => 'Pesagem Oficial | ' . $campeonato['titulo'],
            'campeonato' => $campeonato,
            'inscricoes' => $inscricoes,
            'historicoPesagens' => $historicoPesagens,
            'categorias' => $categorias
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->validateCsrf();

        $idInscricao = (int)$this->post('id_inscricao', 0);
        $idCampeonato = (int)$this->post('id_campeonato', 0);
        $pesoAferido = (float)$this->post('peso_aferido', 0.0);
        $observacoes = $this->post('observacoes', null);
        $operadorId = Session::userId() ?? 1;

        if ($idInscricao <= 0 || $pesoAferido <= 0) {
            Session::flash('error', 'Informe uma inscrição válida e o peso aferido.');
            $this->redirect("/campeonatos/{$idCampeonato}/pesagem");
        }

        try {
            $resultado = PesagemService::processWeighIn($idInscricao, $idCampeonato, $pesoAferido, $operadorId, $observacoes);

            AuditoriaModel::log('REGISTRAR_PESAGEM', 'campeonato_pesagens', $resultado['id_pesagem'], null, $resultado, null, $idCampeonato);

            if ($resultado['resultado'] === 'aprovado') {
                Session::flash('success', "Pesagem APROVADA ({$pesoAferido} kg). Atleta liberado para a chave.");
            } else {
                Session::flash('warning', "Pesagem REPROVADA ({$pesoAferido} kg). {$resultado['motivo']}");
            }
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao processar pesagem: ' . $e->getMessage());
        }

        $this->redirect("/campeonatos/{$idCampeonato}/pesagem");
    }

    public function remanejarCategoria(string $id): void
    {
        $this->requireAdmin();
        $this->validateCsrf();
        $idInscricao = (int)$id;

        $novaCatId = (int)$this->post('nova_categoria_id', 0);
        $justificativa = trim($this->post('justificativa', ''));
        $idCampeonato = (int)$this->post('id_campeonato', 0);

        if ($novaCatId <= 0 || empty($justificativa)) {
            Session::flash('error', 'Selecione a nova categoria e informe a justificativa da alteração.');
            $this->redirect("/campeonatos/{$idCampeonato}/pesagem");
        }

        $inscricao = InscricaoModel::find($idInscricao);
        if (!$inscricao) {
            Session::flash('error', 'Inscrição não encontrada.');
            $this->redirect("/campeonatos/{$idCampeonato}/pesagem");
        }

        $catAntigaId = (int)$inscricao['id_categoria'];

        // Atualiza a categoria e regulariza status para aprovada
        InscricaoModel::updateCategoria($idInscricao, $novaCatId);
        InscricaoModel::updateStatus($idInscricao, 'aprovada', "Remanejado para nova categoria: {$justificativa}");

        AuditoriaModel::log(
            'REMANEJAR_CATEGORIA_PESO',
            'campeonato_inscricoes',
            $idInscricao,
            ['id_categoria' => $catAntigaId],
            ['id_categoria' => $novaCatId],
            $justificativa,
            $idCampeonato
        );

        Session::flash('success', 'Atleta remanejado para a nova categoria com sucesso e liberado para o chaveamento.');
        $this->redirect("/campeonatos/{$idCampeonato}/pesagem");
    }
}
