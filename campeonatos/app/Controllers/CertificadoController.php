<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\CampeonatoModel;
use App\Models\InscricaoModel;
use App\Models\CertificadoModel;
use App\Models\ClassificacaoModel;
use App\Services\PdfService;
use App\Models\AuditoriaModel;

/**
 * Controlador de Certificados Oficiais e Validação Pública por Hash
 */
class CertificadoController extends Controller
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

        $certificados = CertificadoModel::byCampeonato($id);
        $inscricoes = InscricaoModel::byCampeonato($id);

        $this->render('certificados/index', [
            'title' => 'Certificados | ' . $campeonato['titulo'],
            'campeonato' => $campeonato,
            'certificados' => $certificados,
            'inscricoes' => $inscricoes
        ]);
    }

    public function generateSingle(string $idInscricao): void
    {
        $this->requireAuth();
        $idInscricao = (int)$idInscricao;

        $inscricao = InscricaoModel::find($idInscricao);
        if (!$inscricao) {
            Session::flash('error', 'Inscrição não encontrada.');
            $this->redirect('/campeonatos');
        }

        $campeonato = CampeonatoModel::find((int)$inscricao['id_campeonato']);
        $tipo = $this->input('tipo', 'participacao');

        // Gera código hash criptográfico verificável
        $codigoHash = strtoupper(bin2hex(random_bytes(6))) . '-' . date('Y');
        $pdfNome = "cert_{$idInscricao}_" . time() . ".pdf";

        CertificadoModel::create([
            'id_campeonato' => (int)$campeonato['id_campeonato'],
            'id_inscricao' => $idInscricao,
            'nome_titular' => $inscricao['nome_atleta_snapshot'],
            'tipo_certificado' => $tipo,
            'codigo_autenticidade' => $codigoHash,
            'arquivo_pdf_nome' => $pdfNome
        ]);

        AuditoriaModel::log('EMITIR_CERTIFICADO', 'campeonato_certificados', $idInscricao, null, [
            'atleta' => $inscricao['nome_atleta_snapshot'],
            'hash' => $codigoHash
        ], null, (int)$campeonato['id_campeonato']);

        $pdfOutput = PdfService::generateCertificatePdf($campeonato, $inscricao, $tipo, $codigoHash);

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $pdfNome . '"');
        echo $pdfOutput;
        exit;
    }

    public function generateBatch(string $id): void
    {
        $this->requireAdmin();
        $this->validateCsrf();
        $idCampeonato = (int)$id;

        $campeonato = CampeonatoModel::find($idCampeonato);
        if (!$campeonato) {
            Session::flash('error', 'Campeonato não encontrado.');
            $this->redirect('/campeonatos');
        }

        $inscricoes = InscricaoModel::byCampeonato($idCampeonato);
        $emitidos = 0;

        foreach ($inscricoes as $insc) {
            if ($insc['status_elegibilidade'] === 'aprovada') {
                $codigoHash = strtoupper(bin2hex(random_bytes(6))) . '-' . date('Y');
                $pdfNome = "cert_{$insc['id_inscricao']}_" . time() . ".pdf";

                CertificadoModel::create([
                    'id_campeonato' => $idCampeonato,
                    'id_inscricao' => (int)$insc['id_inscricao'],
                    'nome_titular' => $insc['nome_atleta_snapshot'],
                    'tipo_certificado' => 'participacao',
                    'codigo_autenticidade' => $codigoHash,
                    'arquivo_pdf_nome' => $pdfNome
                ]);
                $emitidos++;
            }
        }

        Session::flash('success', "Lote de {$emitidos} certificados gerados com sucesso!");
        $this->redirect("/campeonatos/{$idCampeonato}/certificados");
    }

    public function validatePublic(): void
    {
        $this->render('certificados/validar', [
            'title' => 'Validação Pública de Certificados | Kenshydokan Jutsu',
            'resultado' => null,
            'hashBuscado' => ''
        ], 'layouts/main');
    }

    public function checkHash(): void
    {
        $hash = trim($this->post('codigo_autenticidade', ''));
        $resultado = null;

        if (!empty($hash)) {
            $resultado = CertificadoModel::findByHash($hash);
        }

        $this->render('certificados/validar', [
            'title' => 'Validação Pública de Certificados | Kenshydokan Jutsu',
            'resultado' => $resultado,
            'hashBuscado' => $hash,
            'buscou' => true
        ], 'layouts/main');
    }
}
