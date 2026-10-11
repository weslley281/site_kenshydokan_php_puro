<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\InscricaoModel;
use App\Models\FiliadoModel;
use App\Models\CategoriaModel;
use App\Models\CampeonatoModel;
use App\Models\AuditoriaModel;

/**
 * Controlador de Inscrições e Integração com Filiados
 */
class InscricaoController extends Controller
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

        $idCategoria = $this->input('categoria_id') ? (int)$this->input('categoria_id') : null;
        $inscricoes = InscricaoModel::byCampeonato($id, $idCategoria);
        $categorias = CategoriaModel::byCampeonato($id);

        $this->render('inscricoes/index', [
            'title' => 'Inscrições | ' . $campeonato['titulo'],
            'campeonato' => $campeonato,
            'inscricoes' => $inscricoes,
            'categorias' => $categorias,
            'categoriaAtiva' => $idCategoria
        ]);
    }

    public function create(string $id): void
    {
        $this->requireAuth();
        $id = (int)$id;
        $campeonato = CampeonatoModel::find($id);

        if (!$campeonato) {
            Session::flash('error', 'Campeonato não encontrado.');
            $this->redirect('/campeonatos');
        }

        $categorias = CategoriaModel::byCampeonato($id);

        $this->render('inscricoes/form', [
            'title' => 'Nova Inscrição | ' . $campeonato['titulo'],
            'campeonato' => $campeonato,
            'categorias' => $categorias
        ]);
    }

    /**
     * Endpoint AJAX para busca pesquisável de filiados (Select2)
     */
    public function searchFiliados(): void
    {
        $this->requireAuth();
        $term = trim($this->input('q', ''));
        if (strlen($term) < 2) {
            $this->json(['results' => []]);
        }

        $filiados = FiliadoModel::search($term, 30);
        $results = [];

        foreach ($filiados as $f) {
            $codigo = $f['codigo'] ? " [Cód: {$f['codigo']}]" : '';
            $dn = ($f['dataNascimento'] !== '0000-00-00') ? date('d/m/Y', strtotime($f['dataNascimento'])) : 'Data não informada';
            $results[] = [
                'id' => $f['id_filiado'],
                'text' => "{$f['nome']}{$codigo} - {$f['dojo']} ({$f['graduacao_nome']})",
                'nome' => $f['nome'],
                'dojo' => $f['dojo'],
                'dataNascimento' => $f['dataNascimento'],
                'dataNascimentoFormatada' => $dn,
                'graduacao_id' => $f['id_graduacao'],
                'graduacao_nome' => $f['graduacao_nome']
            ];
        }

        $this->json(['results' => $results]);
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->validateCsrf();

        $idCampeonato = (int)$this->post('id_campeonato', 0);
        $idCategoria = (int)$this->post('id_categoria', 0);
        $idFiliado = (int)$this->post('id_filiado', 0);
        $sexo = $this->post('sexo', 'M');
        $pesoDeclarado = $this->post('peso_declarado') !== '' ? (float)$this->post('peso_declarado') : null;

        $campeonato = CampeonatoModel::find($idCampeonato);
        $categoria = CategoriaModel::find($idCategoria);
        $filiado = FiliadoModel::findById($idFiliado);

        if (!$campeonato || !$categoria || !$filiado) {
            Session::flash('error', 'Dados de inscrição incompletos ou inválidos.');
            $this->redirect("/campeonatos/{$idCampeonato}/inscricoes");
        }

        // 1. Checa duplicidade na mesma categoria
        if (InscricaoModel::isRegistered($idCategoria, $idFiliado)) {
            Session::flash('error', "O atleta {$filiado['nome']} já está inscrito nesta categoria.");
            $this->redirect("/campeonatos/{$idCampeonato}/inscricoes");
        }

        // 2. Valida Data de Nascimento e Idade com base na data de referência do campeonato
        if (empty($filiado['dataNascimento']) || $filiado['dataNascimento'] === '0000-00-00') {
            Session::flash('error', "O filiado {$filiado['nome']} está com data de nascimento inválida no cadastro. Regularize a data antes de inscrever.");
            $this->redirect("/campeonatos/{$idCampeonato}/inscricoes");
        }

        $nasc = new \DateTime($filiado['dataNascimento']);
        $ref = new \DateTime($campeonato['data_referencia_idade']);
        $idade = $ref->diff($nasc)->y;

        // Valida faixa etária da categoria
        if ($idade < (int)$categoria['idade_min'] || $idade > (int)$categoria['idade_max']) {
            Session::flash('error', "Idade do atleta ({$idade} anos) incompatível com a categoria {$categoria['nome']} ({$categoria['idade_min']} a {$categoria['idade_max']} anos).");
            $this->redirect("/campeonatos/{$idCampeonato}/inscricoes/nova");
        }

        // 3. Validação de Sexo
        if ($categoria['sexo'] !== 'misto' && $categoria['sexo'] !== $sexo) {
            Session::flash('error', "Sexo incompatível com a categoria selecionada.");
            $this->redirect("/campeonatos/{$idCampeonato}/inscricoes/nova");
        }

        // 4. Grava Inscrição com Snapshot Imutável
        $statusInicial = ($categoria['exige_pesagem'] == 1) ? 'pendente' : 'aprovada';

        $idInscricao = InscricaoModel::createWithSnapshot([
            'id_campeonato' => $idCampeonato,
            'id_categoria' => $idCategoria,
            'id_filiado' => $idFiliado,
            'nome_atleta_snapshot' => $filiado['nome'],
            'dojo_snapshot' => $filiado['dojo'] ?: 'Independente',
            'graduacao_id_snapshot' => (int)$filiado['id_graduacao'],
            'graduacao_nome_snapshot' => $filiado['graduacao_nome'],
            'data_nascimento_snapshot' => $filiado['dataNascimento'],
            'idade_evento' => $idade,
            'sexo' => $sexo,
            'peso_declarado' => $pesoDeclarado,
            'status_elegibilidade' => $statusInicial
        ]);

        AuditoriaModel::log('INSCREVER_ATLETA', 'campeonato_inscricoes', $idInscricao, null, [
            'atleta' => $filiado['nome'],
            'categoria' => $categoria['nome']
        ], null, $idCampeonato);

        Session::flash('success', "Atleta {$filiado['nome']} inscrito com sucesso na categoria {$categoria['nome']}!");
        $this->redirect("/campeonatos/{$idCampeonato}/inscricoes");
    }

    public function updateStatus(string $id): void
    {
        $this->requireAdmin();
        $this->validateCsrf();
        $id = (int)$id;

        $novoStatus = $this->post('status', 'aprovada');
        $motivo = $this->post('motivo', null);

        InscricaoModel::updateStatus($id, $novoStatus, $motivo);
        AuditoriaModel::log('ALTERAR_STATUS_INSCRICAO', 'campeonato_inscricoes', $id, null, ['status' => $novoStatus, 'motivo' => $motivo]);

        Session::flash('success', "Status da inscrição atualizado para '{$novoStatus}'.");
        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/campeonatos');
    }
}
