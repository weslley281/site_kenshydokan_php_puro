<div class="row justify-content-center py-4">
    <div class="col-lg-8 col-xl-7">
        <div class="text-center mb-4">
            <i class="fas fa-certificate fa-3x text-danger mb-2"></i>
            <h2 class="fw-bold text-dark mb-1">Validação Pública de Certificados</h2>
            <p class="text-muted small">
                Conselho de Mestres e Arbitragem &bull; Kenshydokan Jutsu Oficial
            </p>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <form method="POST" action="/validar-certificado">
                    <div class="mb-3">
                        <label for="codigo_autenticidade" class="form-label fw-bold text-dark">
                            Código Hash de Autenticidade:
                        </label>
                        <div class="input-group input-group-lg">
                            <input type="text" 
                                   class="form-control text-uppercase text-center fw-mono fw-bold" 
                                   id="codigo_autenticidade" 
                                   name="codigo_autenticidade" 
                                   placeholder="EX: A1B2C3D4E5-2026" 
                                   value="<?= htmlspecialchars($hashBuscado ?? '') ?>" 
                                   required 
                                   autofocus>
                            <button class="btn btn-danger px-4 fw-bold" type="submit">
                                <i class="fas fa-search me-1"></i> Verificar
                            </button>
                        </div>
                        <small class="text-muted d-block mt-2">
                            Insira exatamente o código alfanumérico impresso no rodapé ou no verso do certificado.
                        </small>
                    </div>
                </form>
            </div>
        </div>

        <?php if (!empty($buscou)): ?>
            <?php if (!empty($resultado)): ?>
                <!-- Resultado: AUTÊNTICO -->
                <div class="card border-0 shadow-sm border-top border-4 border-success">
                    <div class="card-body p-4 text-center">
                        <div class="mb-3 text-success">
                            <i class="fas fa-check-circle fa-4x animate-pulse"></i>
                        </div>
                        <h4 class="fw-bold text-success mb-1">CERTIFICADO OFICIAL AUTÊNTICO</h4>
                        <p class="text-muted small mb-4">Registro verificado com sucesso no banco de dados da Federação.</p>

                        <div class="p-3 bg-light rounded text-start border small">
                            <div class="row g-2">
                                <div class="col-sm-4 text-muted">Titular / Atleta:</div>
                                <div class="col-sm-8 fw-bold text-dark fs-6"><?= htmlspecialchars($resultado['nome_titular']) ?></div>

                                <div class="col-sm-4 text-muted">Campeonato:</div>
                                <div class="col-sm-8 fw-semibold text-dark"><?= htmlspecialchars($resultado['campeonato_titulo'] ?? 'Evento Oficial') ?></div>

                                <div class="col-sm-4 text-muted">Categoria:</div>
                                <div class="col-sm-8 text-dark"><?= htmlspecialchars($resultado['categoria_nome'] ?? 'Oficial') ?></div>

                                <div class="col-sm-4 text-muted">Tipo de Certificado:</div>
                                <div class="col-sm-8">
                                    <span class="badge bg-danger text-uppercase"><?= htmlspecialchars($resultado['tipo_certificado']) ?></span>
                                </div>

                                <div class="col-sm-4 text-muted">Código Hash:</div>
                                <div class="col-sm-8">
                                    <code class="fw-bold text-dark"><?= htmlspecialchars($resultado['codigo_autenticidade']) ?></code>
                                </div>

                                <div class="col-sm-4 text-muted">Data de Emissão:</div>
                                <div class="col-sm-8 text-dark"><?= date('d/m/Y H:i', strtotime($resultado['data_emissao'])) ?></div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="/campeonatos" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-home me-1"></i> Ir para Página Inicial
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Resultado: NÃO ENCONTRADO -->
                <div class="card border-0 shadow-sm border-top border-4 border-danger">
                    <div class="card-body p-4 text-center">
                        <div class="mb-3 text-danger">
                            <i class="fas fa-times-circle fa-4x"></i>
                        </div>
                        <h4 class="fw-bold text-danger mb-1">CÓDIGO NÃO LOCALIZADO</h4>
                        <p class="text-muted small mb-3">
                            Não encontramos nenhum registro oficial correspondente ao código informado: 
                            <strong class="text-dark"><?= htmlspecialchars($hashBuscado) ?></strong>.
                        </p>
                        <div class="alert alert-warning border-0 small mb-0">
                            Por favor, confira os caracteres digitados. Caso a divergência persista, entre em contato com a organização da Federação Kenshydokan.
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
