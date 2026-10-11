<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\UsuarioModel;
use App\Models\AuditoriaModel;

/**
 * Controlador de Autenticação e Sessão
 */
class AuthController extends Controller
{
    public function loginForm(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        $this->render('auth/login', ['title' => 'Acesso ao Sistema | Kenshydokan'], null);
    }

    public function login(): void
    {
        $this->validateCsrf();

        $email = trim($this->post('email', ''));
        $senha = $this->post('senha', '');

        if (empty($email) || empty($senha)) {
            Session::flash('error', 'Preencha o e-mail e a senha.');
            $this->redirect('/login');
        }

        $user = UsuarioModel::findByEmail($email);

        if (!$user || !password_verify($senha, $user['senha_hash'])) {
            Session::flash('error', 'Credenciais inválidas. Verifique os dados informados.');
            $this->redirect('/login');
        }

        if ($user['status'] !== 'ativo') {
            Session::flash('error', 'Usuário inativo. Entre em contato com a administração.');
            $this->redirect('/login');
        }

        // Sucesso: Regenera ID da sessão para prevenir Session Fixation
        Session::regenerate();
        Session::set('user_id', (int)$user['id_usuario']);
        Session::set('user_role', $user['papel']);
        Session::set('user', [
            'id' => $user['id_usuario'],
            'nome' => $user['nome'],
            'email' => $user['email'],
            'papel' => $user['papel']
        ]);

        UsuarioModel::updateLastLogin((int)$user['id_usuario']);
        AuditoriaModel::log('LOGIN_SUCESSO', 'campeonato_usuarios', (int)$user['id_usuario'], null, ['email' => $email]);

        Session::flash('success', "Bem-vindo de volta, {$user['nome']}!");
        $this->redirect('/dashboard');
    }

    public function logout(): void
    {
        if (Session::isLoggedIn()) {
            AuditoriaModel::log('LOGOUT', 'campeonato_usuarios', (int)Session::userId());
        }
        Session::destroy();
        $this->redirect('/login');
    }
}
