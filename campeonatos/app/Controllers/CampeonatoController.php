<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\CampeonatoModel;
use App\Models\CategoriaModel;
use App\Models\InscricaoModel;
use App\Models\TatameModel;
use App\Models\AuditoriaModel;

/**
 * Controlador de Gestão de Campeonatos
 */
class CampeonatoController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $campeonatos = CampeonatoModel::all();
        $this->render('campeonatos/index', [
            'title' => 'Gestão de Campeonatos | Kenshydokan',
            'campeonatos' => $campeonatos
        ]);
    }

    public function create(): void
    {
        $this->requireAdmin();
        $this->render('campeonatos/form', [
            'title' => 'Novo Campeonato | Kenshydokan',
            'campeonato' => null
        ]);
    }

    public function store(): void
    {
        $this->requireAdmin();
        $this->validateCsrf();

        $titulo = trim($this->post('titulo', ''));
        $dataInicio = $this->post('data_inicio', '');
        $dataFim = $this->post('data_fim', '');
        $dataRef = $this->post('data_referencia_idade', '') ?: $dataInicio;

        if (empty($titulo) || empty($dataInicio) || empty($dataFim)) {
            Session::flash('error', 'Preencha os campos obrigatórios (Título, Data Início e Fim).');
            $this->redirect('/campeonatos/novo');
        }

        $modalidades = $this->post('modalidades', []);
        $modalidadesStr = !empty($modalidades) ? implode(',', $modalidades) : 'kumite,kata,tameshiwari';

        $id = CampeonatoModel::create([
            'titulo' => $titulo,
            'edicao' => $this->post('edicao', ''),
            'ano' => (int)date('Y', strtotime($dataInicio)),
            'local' => $this->post('local', ''),
            'cidade' => $this->post('cidade', ''),
            'estado' => $this->post('estado', ''),
            'data_inicio' => $dataInicio,
            'data_fim' => $dataFim,
            'data_referencia_idade' => $dataRef,
            'organizador' => $this->post('organizador', 'Instituto Kenshydokan'),
            'status' => 'planejamento',
            'modalidades_habilitadas' => $modalidadesStr
        ]);

        AuditoriaModel::log('CRIAR_CAMPEONATO', 'campeonatos', $id, null, ['titulo' => $titulo], null, $id);
        Session::flash('success', "Campeonato '{$titulo}' cadastrado com sucesso!");
        $this->redirect("/campeonatos/{$id}");
    }

    public function show(string $id): void
    {
        $this->requireAuth();
        $id = (int)$id;
        $campeonato = CampeonatoModel::find($id);

        if (!$campeonato) {
            Session::flash('error', 'Campeonato não encontrado.');
            $this->redirect('/campeonatos');
        }

        $categorias = CategoriaModel::byCampeonato($id);
        $inscricoes = InscricaoModel::byCampeonato($id);
        $tatames = TatameModel::byCampeonato($id);

        $this->render('campeonatos/show', [
            'title' => $campeonato['titulo'] . ' | Detalhes',
            'campeonato' => $campeonato,
            'categorias' => $categorias,
            'inscricoes' => $inscricoes,
            'tatames' => $tatames
        ]);
    }

    public function edit(string $id): void
    {
        $this->requireAdmin();
        $id = (int)$id;
        $campeonato = CampeonatoModel::find($id);

        if (!$campeonato) {
            Session::flash('error', 'Campeonato não encontrado.');
            $this->redirect('/campeonatos');
        }

        $this->render('campeonatos/form', [
            'title' => 'Editar Campeonato | ' . $campeonato['titulo'],
            'campeonato' => $campeonato
        ]);
    }

    public function update(string $id): void
    {
        $this->requireAdmin();
        $this->validateCsrf();
        $id = (int)$id;

        $campeonato = CampeonatoModel::find($id);
        if (!$campeonato) {
            Session::flash('error', 'Campeonato não encontrado.');
            $this->redirect('/campeonatos');
        }

        $modalidades = $this->post('modalidades', []);
        $modalidadesStr = !empty($modalidades) ? implode(',', $modalidades) : 'kumite,kata,tameshiwari';

        $data = [
            'titulo' => trim($this->post('titulo', '')),
            'edicao' => $this->post('edicao', ''),
            'ano' => (int)date('Y', strtotime($this->post('data_inicio', ''))),
            'local' => $this->post('local', ''),
            'cidade' => $this->post('cidade', ''),
            'estado' => $this->post('estado', ''),
            'data_inicio' => $this->post('data_inicio', ''),
            'data_fim' => $this->post('data_fim', ''),
            'data_referencia_idade' => $this->post('data_referencia_idade', '') ?: $this->post('data_inicio', ''),
            'organizador' => $this->post('organizador', ''),
            'modalidades_habilitadas' => $modalidadesStr
        ];

        CampeonatoModel::update($id, $data);
        AuditoriaModel::log('EDITAR_CAMPEONATO', 'campeonatos', $id, $campeonato, $data, null, $id);

        Session::flash('success', 'Campeonato atualizado com sucesso!');
        $this->redirect("/campeonatos/{$id}");
    }

    public function changeStatus(string $id): void
    {
        $this->requireAdmin();
        $this->validateCsrf();
        $id = (int)$id;

        $novoStatus = $this->post('status', '');
        $statusValidos = ['planejamento', 'inscricoes_abertas', 'inscricoes_encerradas', 'conferencia_pesagem', 'chaveamento', 'em_andamento', 'encerrado', 'arquivado'];

        if (!in_array($novoStatus, $statusValidos)) {
            Session::flash('error', 'Status inválido.');
            $this->redirect("/campeonatos/{$id}");
        }

        $campeonato = CampeonatoModel::find($id);
        $statusAntigo = $campeonato['status'];

        CampeonatoModel::updateStatus($id, $novoStatus);
        AuditoriaModel::log('MUDANCA_STATUS_CAMPEONATO', 'campeonatos', $id, ['status' => $statusAntigo], ['status' => $novoStatus], "Mudança de ciclo de vida", $id);

        Session::flash('success', "Status do campeonato alterado para '{$novoStatus}'!");
        $this->redirect("/campeonatos/{$id}");
    }
}
