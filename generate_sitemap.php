<?php
// generate_sitemap.php - Gerador automático de sitemap para o Kenshydokan
include_once __DIR__ . "/db/conexao.php";

$c = new Conexao();
$conexao = $c->conectar();

$base_url = "https://kenshydokan.org.br";
$current_date = date("Y-m-d");

$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// 1. Páginas estáticas principais
$paginas_estaticas = [
    "" => ["freq" => "daily", "priority" => "1.0"],
    "/views/inicio.php" => ["freq" => "daily", "priority" => "0.9"],
    "/views/sobre.php" => ["freq" => "monthly", "priority" => "0.7"],
    "/views/filiar.php" => ["freq" => "monthly", "priority" => "0.7"],
    "/views/filiados.php" => ["freq" => "weekly", "priority" => "0.8"],
    "/views/galeria.php" => ["freq" => "weekly", "priority" => "0.7"],
    "/views/postagens.php" => ["freq" => "daily", "priority" => "0.8"],
    "/views/campeonatos.php" => ["freq" => "weekly", "priority" => "0.8"],
    "/views/contato.php" => ["freq" => "monthly", "priority" => "0.6"],
    "/views/login.php" => ["freq" => "monthly", "priority" => "0.5"],
    "/views/cadastrar.php" => ["freq" => "monthly", "priority" => "0.5"],
    "/views/katas.php" => ["freq" => "monthly", "priority" => "0.6"],
    "/views/atemi_waza.php" => ["freq" => "monthly", "priority" => "0.6"],
    "/views/nage_waza.php" => ["freq" => "monthly", "priority" => "0.6"],
    "/views/katame_waza.php" => ["freq" => "monthly", "priority" => "0.6"],
];

foreach ($paginas_estaticas as $path => $meta) {
    $xml .= "  <url>\n";
    $xml .= "    <loc>" . $base_url . $path . "</loc>\n";
    $xml .= "    <lastmod>" . $current_date . "</lastmod>\n";
    $xml .= "    <changefreq>" . $meta['freq'] . "</changefreq>\n";
    $xml .= "    <priority>" . $meta['priority'] . "</priority>\n";
    $xml .= "  </url>\n";
}

if ($conexao) {
    // 2. Páginas de Perfil dos Atletas (Apenas Filiados Confirmados para qualidade de SEO)
    // Buscamos usuários cujo id_fil esteja vinculado e a filiação esteja confirmada
    $query_usuarios = "SELECT u.id_usuario, u.dataMudanca, u.dataCriacao FROM usuarios u 
                       INNER JOIN filiados f ON u.id_fil = f.id_filiado 
                       WHERE f.confirmacao = 'sim'";
    $result_usr = $conexao->query($query_usuarios);
    if ($result_usr) {
        while ($usr = $result_usr->fetch_assoc()) {
            $lastmod = !empty($usr['dataMudanca']) ? $usr['dataMudanca'] : $usr['dataCriacao'];
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . $base_url . "/views/ver_perfil.php?id_usuario=" . $usr['id_usuario'] . "</loc>\n";
            $xml .= "    <lastmod>" . date('Y-m-d', strtotime($lastmod)) . "</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.7</priority>\n";
            $xml .= "  </url>\n";
        }
    }

    // 3. Postagens do Blog (Carregadas por slug se disponível, ou ID)
    $query_postagens = "SELECT id_publicacao, slug, dataMudanca, dataCriacao FROM publicacoes WHERE status = 'aprovado'";
    $result_post = $conexao->query($query_postagens);
    if ($result_post) {
        while ($post = $result_post->fetch_assoc()) {
            $lastmod = !empty($post['dataMudanca']) ? $post['dataMudanca'] : $post['dataCriacao'];
            $link = !empty($post['slug']) ? "/views/postagem.php?slug=" . $post['slug'] : "/views/postagem.php?id=" . $post['id_publicacao'];
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . $base_url . $link . "</loc>\n";
            $xml .= "    <lastmod>" . date('Y-m-d', strtotime($lastmod)) . "</lastmod>\n";
            $xml .= "    <changefreq>monthly</changefreq>\n";
            $xml .= "    <priority>0.7</priority>\n";
            $xml .= "  </url>\n";
        }
    }
}

$xml .= '</urlset>';

// Escreve o arquivo sitemaps.xml
if (file_put_contents(__DIR__ . "/sitemaps.xml", $xml) !== false) {
    echo "Sitemap generated successfully.\n";
} else {
    echo "Error: Failed to write sitemaps.xml.\n";
}
?>
