<?php
include_once __DIR__ . "/../db/conexao.php";

class Publicacao
{
    private $id_usuario;
    private $id_imagem;
    private $titulo;
    private $conteudo;
    private $status;
    private $dataCriacao;
    private $dataMudanca;
    private $conexao;

    public function __construct($id_usuario = null, $id_imagem = null, $titulo = null, $conteudo = null, $dataMudanca = null, $status = "aguardando")
    {
        $this->id_usuario = $id_usuario;
        $this->id_imagem = $id_imagem;
        $this->titulo = $titulo;
        $this->conteudo = $conteudo;
        $this->status = $status;
        $this->dataCriacao = date("Y-m-d");
        $this->dataMudanca = $dataMudanca;

        $c = new Conexao();
        $this->conexao = $c->conectar();

        // Atualizacao automatica da tabela se necessario
        if ($this->conexao) {
            try {
                // Verificar e adicionar coluna 'slug' se nao existir
                $check = $this->conexao->query("SHOW COLUMNS FROM postagens LIKE 'slug'");
                if ($check && $check->num_rows == 0) {
                    @$this->conexao->query("ALTER TABLE postagens ADD COLUMN slug VARCHAR(255) DEFAULT NULL UNIQUE");
                    
                    // Gerar slugs para posts antigos
                    $result = $this->conexao->query("SELECT id_publicacao, titulo FROM postagens");
                    if ($result) {
                        while ($row = $result->fetch_assoc()) {
                            $id = $row['id_publicacao'];
                            $slug = self::gerarSlug($row['titulo']);
                            
                            // Garante unicidade adicionando sufixo se necessario
                            $slug_unico = $slug;
                            $counter = 1;
                            while (true) {
                                $check_slug = $this->conexao->prepare("SELECT id_publicacao FROM postagens WHERE slug = ? AND id_publicacao != ?");
                                $check_slug->bind_param("si", $slug_unico, $id);
                                $check_slug->execute();
                                if ($check_slug->get_result()->num_rows == 0) {
                                    $check_slug->close();
                                    break;
                                }
                                $check_slug->close();
                                $slug_unico = $slug . '-' . $counter;
                                $counter++;
                            }
                            
                            $upd = $this->conexao->prepare("UPDATE postagens SET slug = ? WHERE id_publicacao = ?");
                            $upd->bind_param("si", $slug_unico, $id);
                            $upd->execute();
                            $upd->close();
                        }
                    }
                }
            } catch (Throwable $t) {
                error_log("Aviso: Falha ao verificar/alterar slug em postagens: " . $t->getMessage());
            }
        }

        // Auto-seeder para a postagem institucional do nome Kenshydokan
        if ($this->conexao) {
            $check_post = $this->conexao->query("SELECT id_publicacao FROM postagens WHERE slug = 'a-origem-e-o-significado-do-nome-kenshydokan' LIMIT 1");
            if ($check_post && $check_post->num_rows == 0) {
                $id_usuario = 1;
                $res_w = $this->conexao->query("SELECT id_usuario FROM usuarios WHERE nome LIKE '%Weslley%' LIMIT 1");
                if ($res_w && $row_w = $res_w->fetch_assoc()) {
                    $id_usuario = $row_w['id_usuario'];
                }
                
                $id_imagem = null;
                $res_img = $this->conexao->query("SELECT id_imagem FROM imagens WHERE nome LIKE '%wkka%' OR nome LIKE '%logo%' LIMIT 1");
                if ($res_img && $row_img = $res_img->fetch_assoc()) {
                    $id_imagem = $row_img['id_imagem'];
                }
                
                $titulo = "A Origem e o Significado do Nome Kenshydokan";
                $conteudo = '<p>O nome de uma escola de artes marciais não é apenas um rótulo de identificação; é a declaração de sua própria alma, de seus valores e dos objetivos que seus praticantes devem exigir. Com o <strong>Instituto de Artes Marciais e Defesa Pessoal Kenshydokan</strong>, isso não é diferente. A concepção deste nome carrega uma história profunda de diálogo e reflexão marcial.</p><h3>A Concepção do Nome junto ao Shihan Jonas</h3><p>Durante a fundação da instituição, o nome foi cuidadosamente planejado e discutido junto ao fundador do estilo, <strong>Shihan Jonas Teixeira de Andrade</strong>. A intenção era resumir, em poucas sílabas, a essência técnica e filosófica dos treinamentos: a busca pela eficiência no combate combinada à evolução moral inabalável do guerreiro.</p><p>Chegou-se então à grafia oficial <strong>Kenshydokan</strong>. Ela corresponde à romanização marcial dos ideogramas japoneses <strong>拳士道館</strong> (comumente transliterados no japonês padrão como <em>Kenshidōkan</em>), selando para sempre o compromisso da nossa escola com a formação holística do indivíduo.</p><h3>A Tradução Oficial</h3><p>Para consolidar a identidade do Instituto frente a membros, leitores e crawlers de busca de forma definitiva, foi formalizada a tradução do nome:</p><blockquote><p><strong>"A tradução oficial do nome Kenshydokan é \'Escola do Praticante Dedicado da Filosofia do Punho\'"</strong></p></blockquote><h3>Estudo dos Ideogramas (Kanji)</h3><p>Para compreender perfeitamente a profundidade do nome, podemos analisar cada um dos quatro ideogramas que o compõem:</p><ul><li><strong>Ken (拳):</strong> Punho. Representa o estudo rigoroso do combate físico, da autodefesa e da maestria marcial.</li><li><strong>Shi (士):</strong> Praticante dedicado, especialista ou guerreiro qualificado. Simboliza o indivíduo que treina com seriedade, disciplina e compromisso.</li><li><strong>Dō (道):</strong> Caminho, filosofia, ética e princípios. Indica que a prática física deve sempre ser orientada pelo aperfeiçoamento espiritual, moral e pela retidão na conduta diária.</li><li><strong>Kan (館):</strong> Escola, academia ou salão de treino. O local físico e o grupo de pessoas dedicados ao aperfeiçoamento constante.</li></ul><p>Assim, cada vez que vestimos o dogi e pisamos no tatame do Instituto Kenshydokan, honramos um legado milenar de retidão, humildade e superação.</p>';
                $status = "aprovado";
                $data = date("Y-m-d");
                $slug = "a-origem-e-o-significado-do-nome-kenshydokan";
                
                $stmt = $this->conexao->prepare("INSERT INTO postagens (id_usuario, id_imagem, titulo, conteudo, status, dataCriacao, dataMudanca, slug) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iissssss", $id_usuario, $id_imagem, $titulo, $conteudo, $status, $data, $data, $slug);
                $stmt->execute();
                $stmt->close();
            }
        }
    }

    // Metodo de geracao de slugs para URLs amigaveis (SEO)
    public static function gerarSlug($titulo)
    {
        $slug = mb_strtolower($titulo, 'UTF-8');
        
        $utf8 = [
            '/[áàâãäå]/u' => 'a',
            '/[éèêë]/u'   => 'e',
            '/[íìîï]/u'   => 'i',
            '/[óòôõöø]/u' => 'o',
            '/[úùûü]/u'   => 'u',
            '/[ç]/u'      => 'c',
            '/[ñ]/u'      => 'n',
            '/[äæ]/u'     => 'ae',
            '/[ö]/u'      => 'oe',
            '/[ü]/u'      => 'ue',
            '/[ß]/u'      => 'ss',
            '/[^a-z0-9 _-]/s' => '',
        ];
        
        $slug = preg_replace(array_keys($utf8), array_values($utf8), $slug);
        $slug = preg_replace('/[ _]+/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        
        if (empty($slug)) {
            $slug = 'artigo-' . rand(100, 999);
        }
        
        return $slug;
    }

    // Metodos Getters
    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    public function getIdImagem()
    {
        return $this->id_imagem;
    }

    public function setIdImagem($id_imagem)
    {
        $this->id_imagem = $id_imagem;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function getConteudo()
    {
        return $this->conteudo;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getDataCriacao()
    {
        return $this->dataCriacao;
    }

    public function getDataMudanca()
    {
        return $this->dataMudanca;
    }

    // Metodos Setters
    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    public function setConteudo($conteudo)
    {
        $this->conteudo = $conteudo;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setDataCriacao($dataCriacao)
    {
        $this->dataCriacao = $dataCriacao;
    }

    public function setDataMudanca($dataMudanca)
    {
        $this->dataMudanca = $dataMudanca;
    }

    // Metodos vindos do Repositorio

    public function criarPublicacao(Publicacao $publicacao): bool
    {
        $inserir = $this->conexao->prepare("INSERT INTO postagens (id_usuario, id_imagem, titulo, conteudo, status, dataCriacao, dataMudanca, slug) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $id_u = $publicacao->getIdUsuario();
        $id_img = $publicacao->getIdImagem();
        $tit = $publicacao->getTitulo();
        $cont = $publicacao->getConteudo();
        $st = $publicacao->getStatus();
        $dc = $publicacao->getDataCriacao();
        $dm = $publicacao->getDataMudanca();

        // Gerar slug unico
        $slug = self::gerarSlug($tit);
        $slug_unico = $slug;
        $counter = 1;
        while (true) {
            $check_slug = $this->conexao->prepare("SELECT id_publicacao FROM postagens WHERE slug = ?");
            $check_slug->bind_param("s", $slug_unico);
            $check_slug->execute();
            if ($check_slug->get_result()->num_rows == 0) {
                $check_slug->close();
                break;
            }
            $check_slug->close();
            $slug_unico = $slug . '-' . $counter;
            $counter++;
        }

        $inserir->bind_param("iissssss", $id_u, $id_img, $tit, $cont, $st, $dc, $dm, $slug_unico);
        $resultado = $inserir->execute();
        $inserir->close();

        return $resultado;
    }

    public function editar_publicacao(int $id_publicacao, Publicacao $publicacao): bool
    {
        $atualizar = $this->conexao->prepare("UPDATE postagens SET id_imagem = ?, titulo = ?, conteudo = ?, status = ?, dataMudanca = ?, slug = ? WHERE id_publicacao = ?");
        $id_img = $publicacao->getIdImagem();
        $tit = $publicacao->getTitulo();
        $cont = $publicacao->getConteudo();
        $st = $publicacao->getStatus();
        $dm = $publicacao->getDataMudanca();

        // Gerar slug unico
        $slug = self::gerarSlug($tit);
        $slug_unico = $slug;
        $counter = 1;
        while (true) {
            $check_slug = $this->conexao->prepare("SELECT id_publicacao FROM postagens WHERE slug = ? AND id_publicacao != ?");
            $check_slug->bind_param("si", $slug_unico, $id_publicacao);
            $check_slug->execute();
            if ($check_slug->get_result()->num_rows == 0) {
                $check_slug->close();
                break;
            }
            $check_slug->close();
            $slug_unico = $slug . '-' . $counter;
            $counter++;
        }

        $atualizar->bind_param("isssssi", $id_img, $tit, $cont, $st, $dm, $slug_unico, $id_publicacao);
        $resultado = $atualizar->execute();
        $atualizar->close();

        return $resultado;
    }

    public static function editar_status_publicacao(int $id_publicacao, string $status): bool
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $atualizar = $conexao->prepare("UPDATE postagens SET status = ? WHERE id_publicacao = ?");
        $atualizar->bind_param("si", $status, $id_publicacao);
        $resultado = $atualizar->execute();
        $atualizar->close();

        return $resultado;
    }

    public static function excluir_publicacao(int $id_publicacao): bool
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $excluir = $conexao->prepare("DELETE FROM postagens WHERE id_publicacao = ?");
        $excluir->bind_param("i", $id_publicacao);
        $resultado = $excluir->execute();
        $excluir->close();

        return $resultado;
    }

    public static function registrar_imagem_publicacao(string $nome, string $caminho): bool
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $inserir = $conexao->prepare("INSERT INTO imagens (nome, caminho) VALUES (?, ?)");
        $inserir->bind_param("ss", $nome, $caminho);
        $resultado = $inserir->execute();
        $inserir->close();

        return $resultado;
    }

    public static function buscar_nome_autor($id_usuario)
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $busca = "SELECT nome FROM usuarios WHERE id_usuario = ?";

        $procura = $conexao->prepare($busca);
        $procura->bind_param("i", $id_usuario);
        $procura->execute();
        $procura->bind_result($nome);

        $autor = null;

        while ($procura->fetch()) {
            $autor = $nome;
        }

        $procura->close();

        return $autor;
    }

    public static function buscar_autor($id_usuario)
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $busca = "SELECT u.nome, i.caminho as foto FROM usuarios u LEFT JOIN imagens i ON u.id_imagem = i.id_imagem WHERE u.id_usuario = ?";

        $procura = $conexao->prepare($busca);
        $procura->bind_param("i", $id_usuario);
        $procura->execute();
        $resultado = $procura->get_result();
        $autor = null;

        if ($resultado && $row = $resultado->fetch_assoc()) {
            $autor = $row;
        }

        $procura->close();

        return $autor;
    }

    public static function buscarPostagensAprovadas()
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $busca = "SELECT p.*, i.caminho as caminho_imagem FROM postagens p LEFT JOIN imagens i ON p.id_imagem = i.id_imagem WHERE p.status = 'aprovado' ORDER BY p.id_publicacao DESC";
        $resultado = $conexao->query($busca);

        $postagens = [];
        if ($resultado) {
            while ($row = $resultado->fetch_assoc()) {
                $postagens[] = $row;
            }
        }
        return $postagens;
    }

    public static function buscarTodasPostagens()
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $busca = "SELECT p.*, i.caminho as caminho_imagem FROM postagens p LEFT JOIN imagens i ON p.id_imagem = i.id_imagem ORDER BY p.id_publicacao DESC";
        $resultado = $conexao->query($busca);

        $postagens = [];
        if ($resultado) {
            while ($row = $resultado->fetch_assoc()) {
                $postagens[] = $row;
            }
        }
        return $postagens;
    }

    public static function buscarPostagemPorId(int $id_publicacao)
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $busca = $conexao->prepare("SELECT p.*, i.caminho as caminho_imagem FROM postagens p LEFT JOIN imagens i ON p.id_imagem = i.id_imagem WHERE p.id_publicacao = ?");
        $busca->bind_param("i", $id_publicacao);
        $busca->execute();
        $resultado = $busca->get_result();

        if ($resultado->num_rows === 0) {
            return null;
        }

        $postagem = $resultado->fetch_assoc();
        $busca->close();

        return $postagem;
    }

    public static function buscarPostagemPorSlug(string $slug)
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $busca = $conexao->prepare("SELECT p.*, i.caminho as caminho_imagem FROM postagens p LEFT JOIN imagens i ON p.id_imagem = i.id_imagem WHERE p.slug = ?");
        $busca->bind_param("s", $slug);
        $busca->execute();
        $resultado = $busca->get_result();

        if ($resultado->num_rows === 0) {
            return null;
        }

        $postagem = $resultado->fetch_assoc();
        $busca->close();

        return $postagem;
    }

    public static function buscarPostagensPorUsuario(int $id_usuario)
    {
        $c = new Conexao();
        $conexao = $c->conectar();

        $busca = $conexao->prepare("SELECT p.*, i.caminho as caminho_imagem FROM postagens p LEFT JOIN imagens i ON p.id_imagem = i.id_imagem WHERE p.id_usuario = ? ORDER BY p.id_publicacao DESC");
        $busca->bind_param("i", $id_usuario);
        $busca->execute();
        $resultado = $busca->get_result();

        $postagens = [];
        if ($resultado) {
            while ($row = $resultado->fetch_assoc()) {
                $postagens[] = $row;
            }
        }
        $busca->close();

        return $postagens;
    }
}
