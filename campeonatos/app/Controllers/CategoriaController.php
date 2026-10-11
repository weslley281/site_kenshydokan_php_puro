<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\CategoriaModel;
use App\Models\CampeonatoModel;
use App\Models\AuditoriaModel;

/**
 * Controlador de Gestão de Categorias
 */
class CategoriaController extends Controller
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

        $this->render('categorias/index', [
            'title' => 'Categorias | ' . $campeonato['titulo'],
            'campeonato' => $campeonato,
            'categorias' => $categorias
        ]);
    }

    public function store(): void
    {
        $this->requireAdmin();
        $this->validateCsrf();

        $idCampeonato = (int)$this->post('id_campeonato', 0);
        $nome = trim($this->post('nome', ''));
        $modalidade = $this->post('modalidade', 'kumite');
        $sexo = $this->post('sexo', 'misto');

        if (empty($nome) || $idCampeonato <= 0) {
            Session::flash('error', 'Nome da categoria é obrigatório.');
            $this->redirect("/campeonatos/{$idCampeonato}/categorias");
        }

        $pesoMin = $this->post('peso_min') !== '' ? (float)$this->post('peso_min') : null;
        $pesoMax = $this->post('peso_max') !== '' ? (float)$this->post('peso_max') : null;

        $catId = CategoriaModel::create([
            'id_campeonato' => $idCampeonato,
            'modalidade' => $modalidade,
            'nome' => $nome,
            'sexo' => $sexo,
            'idade_min' => (int)$this->post('idade_min', 0),
            'idade_max' => (int)$this->post('idade_max', 99),
            'peso_min' => $pesoMin,
            'peso_max' => $pesoMax,
            'graduacao_inicio_id' => $this->post('graduacao_inicio_id') ? (int)$this->post('graduacao_inicio_id') : null,
            'graduacao_fim_id' => $this->post('graduacao_fim_id') ? (int)$this->post('graduacao_fim_id') : null,
            'nivel_competitivo' => $this->post('nivel_competitivo', 'Geral'),
            'exige_pesagem' => (int)$this->post('exige_pesagem', 1)
        ]);

        AuditoriaModel::log('CRIAR_CATEGORIA', 'campeonato_categorias', $catId, null, ['nome' => $nome], null, $idCampeonato);
        Session::flash('success', "Categoria '{$nome}' cadastrada com sucesso!");
        $this->redirect("/campeonatos/{$idCampeonato}/categorias");
    }

    public function destroy(string $id): void
    {
        $this->requireAdmin();
        $this->validateCsrf();
        $id = (int)$id;

        $categoria = CategoriaModel::find($id);
        if ($categoria) {
            $idCampeonato = (int)$categoria['id_campeonato'];
            CategoriaModel::delete($id);
            AuditoriaModel::log('EXCLUIR_CATEGORIA', 'campeonato_categorias', $id, $categoria, null, null, $idCampeonato);
            Session::flash('success', 'Categoria removida com sucesso!');
            $this->redirect("/campeonatos/{$idCampeonato}/categorias");
        }

        Session::flash('error', 'Categoria não encontrada.');
        $this->redirect('/campeonatos');
    }

    public function loadDefaultTemplate(string $id): void
    {
        $this->requireAdmin();
        $this->validateCsrf();
        $id = (int)$id;

        CategoriaModel::createDefaultTemplate($id);
        AuditoriaModel::log('CARGA_TEMPLATE_CATEGORIAS', 'campeonatos', $id, null, null, 'Carga do modelo padrão', $id);

        Session::flash('success', 'Categorias do modelo oficial importadas com sucesso!');
        $this->redirect("/campeonatos/{$id}/categorias");
    }
}
