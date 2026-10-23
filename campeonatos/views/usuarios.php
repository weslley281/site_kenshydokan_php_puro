<?php
require_once __DIR__ . "/../controllers/authHelper.php";
exigirPerfil([], 'inicio.php'); // somente administrador

$pageTitle = 'Usuários | Kenshydokan';
include_once __DIR__ . "/menu.php";
include_once __DIR__ . "/../models/usuarioModel.php";

$usuarioModel = new Usuario();
$usuarios = $usuarioModel->listarTodos();
$idLogado = (int)($_SESSION['id_usuario'] ?? 0);

$perfis = [
    'admin'   => ['nome' => 'Administrador', 'cor' => 'danger',    'ajuda' => 'Pode tudo, inclusive criar campeonatos e usuários.'],
    'mesario' => ['nome' => 'Mesário',       'cor' => 'dark',      'ajuda' => 'Inscreve atletas, pesa e opera a súmula e o tatame.'],
    'arbitro' => ['nome' => 'Árbitro',       'cor' => 'secondary', 'ajuda' => 'Consulta as súmulas, sem lançar pontos.'],
    'leitor'  => ['nome' => 'Leitor',        'cor' => 'light text-dark border', 'ajuda' => 'Só consulta. Não altera nada.'],
];
?>

<div class="page-head">
    <div>
        <h1>Usuários</h1>
        <p>Quem pode entrar no sistema e o que cada pessoa pode fazer.</p>
    </div>
    <button type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#modalNovoUsuario">
        <i class="fas fa-user-plus me-2"></i>Novo usuário
    </button>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h2 class="h6 fw-bold mb-3">O que cada perfil pode fazer</h2>
        <div class="row g-2 small">
            <?php foreach ($perfis as $chave => $p): ?>
                <div class="col-md-6 col-xl-3">
                    <span class="badge bg-<?= $p['cor'] ?> me-1"><?= $p['nome'] ?></span>
                    <span class="text-muted"><?= $p['ajuda'] ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Perfil</th>
                    <th>Situação</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u):
                    $uid = (int)$u['id_usuario'];
                    $ehEu = $uid === $idLogado;
                    $perfil = $perfis[$u['nivel']] ?? ['nome' => $u['nivel'], 'cor' => 'secondary'];
                    $ativo = !empty($u['ativo']);
                ?>
                    <tr class="<?= $ativo ? '' : 'text-muted' ?>">
                        <td class="fw-bold">
                            <?= htmlspecialchars($u['nome']) ?>
                            <?php if ($ehEu): ?><span class="badge bg-light text-dark border ms-1">você</span><?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><span class="badge bg-<?= $perfil['cor'] ?>"><?= htmlspecialchars($perfil['nome']) ?></span></td>
                        <td>
                            <?php if ($ativo): ?>
                                <span class="badge bg-success">Ativo</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Desativado</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end text-nowrap">
                            <button type="button" class="btn btn-outline-dark btn-sm btn-editar"
                                    data-id="<?= $uid ?>"
                                    data-nome="<?= htmlspecialchars($u['nome'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-email="<?= htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-nivel="<?= htmlspecialchars($u['nivel'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-bs-toggle="modal" data-bs-target="#modalEditarUsuario">
                                <i class="fas fa-pen me-1"></i>Editar
                            </button>
                            <button type="button" class="btn btn-outline-dark btn-sm btn-senha"
                                    data-id="<?= $uid ?>"
                                    data-nome="<?= htmlspecialchars($u['nome'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-bs-toggle="modal" data-bs-target="#modalSenhaUsuario">
                                <i class="fas fa-key me-1"></i>Nova senha
                            </button>
                            <?php if (!$ehEu): ?>
                                <form method="POST" action="../controllers/usuarioController.php" class="d-inline"
                                      onsubmit="return confirm('<?= $ativo ? 'Desativar este usuário? Ele não conseguirá mais entrar.' : 'Reativar este usuário?' ?>');"><?= csrfCampo() ?>
                                    <input type="hidden" name="tipo" value="alternar_ativo">
                                    <input type="hidden" name="id_usuario" value="<?= $uid ?>">
                                    <input type="hidden" name="ativar" value="<?= $ativo ? '0' : '1' ?>">
                                    <button type="submit" class="btn btn-sm <?= $ativo ? 'btn-outline-danger' : 'btn-outline-success' ?>">
                                        <?php if ($ativo): ?><i class="fas fa-user-slash me-1"></i>Desativar<?php else: ?><i class="fas fa-user-check me-1"></i>Reativar<?php endif; ?>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: novo usuário -->
<div class="modal fade" id="modalNovoUsuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="../controllers/usuarioController.php"><?= csrfCampo() ?>
                <input type="hidden" name="tipo" value="criar_usuario">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fs-6 fw-bold"><i class="fas fa-user-plus text-danger me-2"></i>Novo usuário</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="novoNome" class="form-label fw-bold">Nome</label>
                        <input type="text" class="form-control form-control-lg" id="novoNome" name="nome" required maxlength="150">
                    </div>
                    <div class="mb-3">
                        <label for="novoEmail" class="form-label fw-bold">E-mail (usado para entrar)</label>
                        <input type="email" class="form-control form-control-lg" id="novoEmail" name="email" required maxlength="150">
                    </div>
                    <div class="mb-3">
                        <label for="novoNivel" class="form-label fw-bold">O que essa pessoa vai fazer?</label>
                        <select class="form-select form-select-lg" id="novoNivel" name="nivel" required>
                            <?php foreach ($perfis as $chave => $p): ?>
                                <option value="<?= $chave ?>" <?= $chave === 'mesario' ? 'selected' : '' ?>><?= $p['nome'] ?> - <?= $p['ajuda'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-1">
                        <label for="novaSenha" class="form-label fw-bold">Senha inicial</label>
                        <input type="password" class="form-control form-control-lg" id="novaSenha" name="senha" required minlength="8" autocomplete="new-password">
                        <div class="form-text">Mínimo de 8 caracteres. Passe a senha para a pessoa por um canal seguro.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger fw-bold">Criar usuário</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: editar usuário -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="../controllers/usuarioController.php"><?= csrfCampo() ?>
                <input type="hidden" name="tipo" value="editar_usuario">
                <input type="hidden" name="id_usuario" id="editId">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fs-6 fw-bold"><i class="fas fa-pen text-danger me-2"></i>Editar usuário</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editNome" class="form-label fw-bold">Nome</label>
                        <input type="text" class="form-control form-control-lg" id="editNome" name="nome" required maxlength="150">
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label fw-bold">E-mail</label>
                        <input type="email" class="form-control form-control-lg" id="editEmail" name="email" required maxlength="150">
                    </div>
                    <div class="mb-1">
                        <label for="editNivel" class="form-label fw-bold">Perfil</label>
                        <select class="form-select form-select-lg" id="editNivel" name="nivel" required>
                            <?php foreach ($perfis as $chave => $p): ?>
                                <option value="<?= $chave ?>"><?= $p['nome'] ?> - <?= $p['ajuda'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger fw-bold">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: nova senha -->
<div class="modal fade" id="modalSenhaUsuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="../controllers/usuarioController.php"><?= csrfCampo() ?>
                <input type="hidden" name="tipo" value="redefinir_senha">
                <input type="hidden" name="id_usuario" id="senhaId">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fs-6 fw-bold"><i class="fas fa-key text-danger me-2"></i>Nova senha</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Definir uma nova senha para <strong id="senhaNome"></strong>.</p>
                    <label for="senhaNova" class="form-label fw-bold">Nova senha</label>
                    <input type="password" class="form-control form-control-lg" id="senhaNova" name="senha" required minlength="8" autocomplete="new-password">
                    <div class="form-text">Mínimo de 8 caracteres.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger fw-bold">Salvar nova senha</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.btn-editar').forEach(function (botao) {
    botao.addEventListener('click', function () {
        document.getElementById('editId').value = botao.dataset.id;
        document.getElementById('editNome').value = botao.dataset.nome;
        document.getElementById('editEmail').value = botao.dataset.email;
        document.getElementById('editNivel').value = botao.dataset.nivel;
    });
});
document.querySelectorAll('.btn-senha').forEach(function (botao) {
    botao.addEventListener('click', function () {
        document.getElementById('senhaId').value = botao.dataset.id;
        document.getElementById('senhaNome').textContent = botao.dataset.nome;
        document.getElementById('senhaNova').value = '';
    });
});
</script>

<?php include_once __DIR__ . "/rodape.php"; ?>
