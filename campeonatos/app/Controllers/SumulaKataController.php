<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\CategoriaModel;
use App\Models\InscricaoModel;
use App\Models\SumulaKataModel;
use App\Services\KataEngineService;
use App\Models\AuditoriaModel;

/**
 * Controlador de Súmula Digital de Kata
 */
class SumulaKataController extends Controller
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
        $ranking = SumulaKataModel::getRanking($idCategoria, 1);
        $precisaDesempate = KataEngineService::checkTieBreakRequired($ranking);

        $this->render('sumulas/kata', [
            'title' => 'Súmula de Kata | ' . $categoria['nome'],
            'campeonato' => $campeonato,
            'categoria' => $categoria,
            'inscricoes' => $inscricoes,
            'ranking' => $ranking,
            'precisaDesempate' => $precisaDesempate
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->validateCsrf();

        $idCampeonato = (int)$this->post('id_campeonato', 0);
        $idCategoria = (int)$this->post('id_categoria', 0);
        $idInscricao = (int)$this->post('id_inscricao', 0);
        $kataNome = trim($this->post('kata_nome', ''));
        $notas = $this->post('notas', []); // array de notas dos árbitros [1 => 8.5, 2 => 8.7, ...]
        $arbitros = $this->post('arbitros', []); // array de nomes dos árbitros

        if (empty($notas) || $idInscricao <= 0) {
            Session::flash('error', 'Informe o atleta e as notas dos árbitros.');
            $this->redirect("/sumula/kata/{$idCategoria}");
        }

        foreach ($notas as $ordem => $notaVal) {
            $notaNum = (float)$notaVal;
            $arbNome = trim($arbitros[$ordem] ?? "Árbitro {$ordem}");
            if ($notaNum >= 0.0 && $notaNum <= 10.0) {
                SumulaKataModel::recordScore([
                    'id_campeonato' => $idCampeonato,
                    'id_categoria' => $idCategoria,
                    'id_inscricao' => $idInscricao,
                    'rodada' => 1,
                    'is_desempate' => 0,
                    'kata_nome' => $kataNome,
                    'arbitro_ordem' => (int)$ordem,
                    'arbitro_nome' => $arbNome,
                    'nota' => $notaNum,
                    'observacao' => null
                ]);
            }
        }

        AuditoriaModel::log('LANCAR_NOTAS_KATA', 'campeonato_notas_kata', $idInscricao, null, [
            'kata' => $kataNome,
            'notas' => $notas
        ], null, $idCampeonato);

        Session::flash('success', 'Notas de Kata registradas e média calculada com sucesso!');
        $this->redirect("/sumula/kata/{$idCategoria}");
    }

    public function tiebreak(): void
    {
        $this->requireAuth();
        $this->validateCsrf();

        $idCampeonato = (int)$this->post('id_campeonato', 0);
        $idCategoria = (int)$this->post('id_categoria', 0);
        $idInscricao = (int)$this->post('id_inscricao', 0);
        $kataNome = trim($this->post('kata_nome', ''));
        $notas = $this->post('notas', []);
        $arbitros = $this->post('arbitros', []);

        foreach ($notas as $ordem => $notaVal) {
            $notaNum = (float)$notaVal;
            $arbNome = trim($arbitros[$ordem] ?? "Árbitro {$ordem}");
            SumulaKataModel::recordScore([
                'id_campeonato' => $idCampeonato,
                'id_categoria' => $idCategoria,
                'id_inscricao' => $idInscricao,
                'rodada' => 2, // Rodada de Desempate
                'is_desempate' => 1,
                'kata_nome' => $kataNome,
                'arbitro_ordem' => (int)$ordem,
                'arbitro_nome' => $arbNome,
                'nota' => $notaNum,
                'observacao' => 'Desempate oficial com novo Kata'
            ]);
        }

        AuditoriaModel::log('DESEMPATE_KATA', 'campeonato_notas_kata', $idInscricao, null, ['notas' => $notas], 'Novo Kata de desempate', $idCampeonato);
        Session::flash('success', 'Notas de desempate registradas com sucesso!');
        $this->redirect("/sumula/kata/{$idCategoria}");
    }
}
