<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\CampeonatoModel;
use App\Models\TatameModel;
use App\Models\LutaModel;
use App\Models\AuditoriaModel;

/**
 * Controlador de Gestão de Tatames e Filas de Combate
 */
class TatameController extends Controller
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

        $tatames = TatameModel::byCampeonato($id);

        $this->render('tatames/index', [
            'title' => 'Gestão de Tatames | ' . $campeonato['titulo'],
            'campeonato' => $campeonato,
            'tatames' => $tatames
        ]);
    }

    public function store(): void
    {
        $this->requireAdmin();
        $this->validateCsrf();

        $idCampeonato = (int)$this->post('id_campeonato', 0);
        $numero = (int)$this->post('numero', 1);
        $nome = trim($this->post('nome', 'Tatame ' . $numero));

        $idTatame = TatameModel::create([
            'id_campeonato' => $idCampeonato,
            'numero' => $numero,
            'nome' => $nome,
            'status' => 'disponivel'
        ]);

        AuditoriaModel::log('CRIAR_TATAME', 'campeonato_tatames', $idTatame, null, ['nome' => $nome], null, $idCampeonato);
        Session::flash('success', "Tatame '{$nome}' cadastrado com sucesso!");
        $this->redirect("/campeonatos/{$idCampeonato}/tatames");
    }

    public function panel(string $idTatame): void
    {
        $this->requireAuth();
        $idTatame = (int)$idTatame;

        $tatame = TatameModel::find($idTatame);
        if (!$tatame) {
            Session::flash('error', 'Tatame não encontrado.');
            $this->redirect('/campeonatos');
        }

        $campeonato = CampeonatoModel::find((int)$tatame['id_campeonato']);
        $lutas = LutaModel::byTatame($idTatame);

        $this->render('tatames/panel', [
            'title' => 'Mesa: ' . $tatame['nome'] . ' | ' . $campeonato['titulo'],
            'campeonato' => $campeonato,
            'tatame' => $tatame,
            'lutas' => $lutas
        ]);
    }

    public function bigScreen(string $idTatame): void
    {
        $idTatame = (int)$idTatame;
        $tatame = TatameModel::find($idTatame);

        if (!$tatame) {
            echo "Tatame não encontrado.";
            exit;
        }

        $campeonato = CampeonatoModel::find((int)$tatame['id_campeonato']);
        $lutaAtual = $tatame['luta_em_andamento_id'] ? LutaModel::find((int)$tatame['luta_em_andamento_id']) : null;
        $proximasLutas = LutaModel::byTatame($idTatame, 'chamada');

        $this->render('tatames/bigscreen', [
            'title' => 'Telão ' . $tatame['nome'] . ' | ' . $campeonato['titulo'],
            'campeonato' => $campeonato,
            'tatame' => $tatame,
            'lutaAtual' => $lutaAtual,
            'proximasLutas' => $proximasLutas
        ], 'layouts/tatame');
    }
}
