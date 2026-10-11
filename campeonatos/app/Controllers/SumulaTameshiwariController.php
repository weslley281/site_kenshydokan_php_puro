<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\CategoriaModel;
use App\Models\InscricaoModel;
use App\Models\SumulaTameshiwariModel;
use App\Models\AuditoriaModel;

/**
 * Controlador de Súmula Digital de Tameshiwari (Quebramento)
 */
class SumulaTameshiwariController extends Controller
{
    public function show(string $idCategoria): void
    {
        $this->requireAuth();
        $idCategoria = (int)$idCategoria;

        $categoria = CategoriaModel::find($idCategoria);
        if (!$categoria) {
            Session::flash('error', 'Categoria não encontrada.');
            $this->redirect('/campeonatos');
        }

        $campeonato = \App\Models\CampeonatoModel::find((int)$categoria['id_campeonato']);
        $inscricoes = InscricaoModel::byCampeonato((int)$campeonato['id_campeonato'], $idCategoria);
        $ranking = SumulaTameshiwariModel::getRanking($idCategoria);

        $this->render('sumulas/tameshiwari', [
            'title' => 'Súmula de Tameshiwari | ' . $categoria['nome'],
            'campeonato' => $campeonato,
            'categoria' => $categoria,
            'inscricoes' => $inscricoes,
            'ranking' => $ranking
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->validateCsrf();

        $idCampeonato = (int)$this->post('id_campeonato', 0);
        $idCategoria = (int)$this->post('id_categoria', 0);
        $idInscricao = (int)$this->post('id_inscricao', 0);
        $tecnica = $this->post('tecnica', 'seiken');
        $material = $this->post('material', 'madeira');
        $qtdDeclarada = (int)$this->post('qtd_declarada', 1);
        $qtdQuebrada = (int)$this->post('qtd_quebrada', 0);
        $resultado = $this->post('resultado', 'falha_ou_parcial');
        $tempoSegundos = (int)$this->post('tempo_segundos', 0);
        $arbitroNome = trim($this->post('arbitro_responsavel', 'Árbitro de Tameshiwari'));
        $observacoes = $this->post('observacoes', null);

        // Regra Oficial: falha ou quebramento parcial resulta em nota zero
        if ($resultado === 'falha_ou_parcial') {
            $qtdQuebrada = 0;
        }

        SumulaTameshiwariModel::recordAttempt([
            'id_campeonato' => $idCampeonato,
            'id_categoria' => $idCategoria,
            'id_inscricao' => $idInscricao,
            'tecnica' => $tecnica,
            'material' => $material,
            'qtd_declarada' => $qtdDeclarada,
            'qtd_quebrada' => $qtdQuebrada,
            'resultado' => $resultado,
            'tempo_segundos' => $tempoSegundos,
            'arbitro_responsavel' => $arbitroNome,
            'observacoes' => $observacoes
        ]);

        AuditoriaModel::log('REGISTRAR_TAMESHIWARI', 'campeonato_tentativas_tameshiwari', $idInscricao, null, [
            'tecnica' => $tecnica,
            'qtd_declarada' => $qtdDeclarada,
            'qtd_quebrada' => $qtdQuebrada,
            'resultado' => $resultado
        ], null, $idCampeonato);

        Session::flash('success', "Tentativa de Tameshiwari na técnica " . strtoupper($tecnica) . " registrada com sucesso!");
        $this->redirect("/sumula/tameshiwari/{$idCategoria}");
    }
}
