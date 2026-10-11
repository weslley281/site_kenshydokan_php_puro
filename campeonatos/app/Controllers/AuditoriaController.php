<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\CampeonatoModel;
use App\Models\AuditoriaModel;

/**
 * Controlador de Trilha de Auditoria
 */
class AuditoriaController extends Controller
{
    public function index(string $id): void
    {
        $this->requireAdmin();
        $id = (int)$id;
        $campeonato = CampeonatoModel::find($id);

        if (!$campeonato) {
            $this->redirect('/campeonatos');
        }

        $logs = AuditoriaModel::byCampeonato($id, 200);

        $this->render('auditoria/index', [
            'title' => 'Trilha de Auditoria | ' . $campeonato['titulo'],
            'campeonato' => $campeonato,
            'logs' => $logs
        ]);
    }
}
