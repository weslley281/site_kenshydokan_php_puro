<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\CampeonatoModel;
use App\Core\Database;

/**
 * Controlador do Dashboard e Indicadores Estatísticos
 */
class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        $conn = Database::getConnection();

        // Estatísticas Rápidas
        $campeonatos = CampeonatoModel::all();
        $totalCampeonatos = count($campeonatos);

        $resAtletas = $conn->query("SELECT COUNT(*) AS total FROM campeonato_inscricoes");
        $totalAtletas = (int)($resAtletas->fetch_assoc()['total'] ?? 0);

        $resLutas = $conn->query("SELECT COUNT(*) AS total FROM campeonato_lutas");
        $totalLutas = (int)($resLutas->fetch_assoc()['total'] ?? 0);

        $resTatames = $conn->query("SELECT COUNT(*) AS total FROM campeonato_tatames");
        $totalTatames = (int)($resTatames->fetch_assoc()['total'] ?? 0);

        // Lutas Recentes / Em Andamento
        $resLutasAndamento = $conn->query("
            SELECT l.*, c.titulo AS campeonato_titulo, cat.nome AS categoria_nome, t.nome AS tatame_nome,
                   aka.nome_atleta_snapshot AS aka_nome, shiro.nome_atleta_snapshot AS shiro_nome
            FROM campeonato_lutas l
            JOIN campeonatos c ON l.id_campeonato = c.id_campeonato
            JOIN campeonato_categorias cat ON l.id_categoria = cat.id_categoria
            LEFT JOIN campeonato_tatames t ON l.id_tatame = t.id_tatame
            LEFT JOIN campeonato_inscricoes aka ON l.id_inscricao_aka = aka.id_inscricao
            LEFT JOIN campeonato_inscricoes shiro ON l.id_inscricao_shiro = shiro.id_inscricao
            WHERE l.status IN ('em_andamento', 'chamada')
            ORDER BY l.ordem_fila ASC
            LIMIT 5
        ");
        $lutasAndamento = $resLutasAndamento->fetch_all(MYSQLI_ASSOC);

        // Dados para Gráficos
        $resMod = $conn->query("
            SELECT modalidade, COUNT(*) AS total
            FROM campeonato_categorias
            GROUP BY modalidade
        ");
        $distModalidades = $resMod->fetch_all(MYSQLI_ASSOC);

        $this->render('dashboard/index', [
            'title' => 'Dashboard | Kenshydokan Jutsu',
            'campeonatos' => $campeonatos,
            'totalCampeonatos' => $totalCampeonatos,
            'totalAtletas' => $totalAtletas,
            'totalLutas' => $totalLutas,
            'totalTatames' => $totalTatames,
            'lutasAndamento' => $lutasAndamento,
            'distModalidades' => $distModalidades
        ]);
    }
}
