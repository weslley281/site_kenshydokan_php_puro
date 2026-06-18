<?php
include_once __DIR__ . "/../db/conexao.php";

class Campeonato
{
    private $id_campeonato;
    private $titulo;
    private $subtitulo;
    private $endereco;
    private $ativo;
    private $dataCriacao;
    private $dataMudanca;
    private $conexao;

    public function __construct($id_campeonato = null, $titulo = null, $subtitulo = null, $endereco = null, $ativo = null, $dataMudanca = null)
    {
        $this->id_campeonato = $id_campeonato;
        $this->titulo = $titulo;
        $this->subtitulo = $subtitulo;
        $this->endereco = $endereco;
        $this->ativo = $ativo;
        $this->dataCriacao = date("Y-m-d");
        $this->dataMudanca = $dataMudanca;

        $c = new Conexao();
        $this->conexao = $c->conectar();
    }

    // Getters e Setters aqui...

    public function buscarTodos()
    {
        $campeonatos = [];
        $busca = "SELECT * FROM campeonatos ORDER BY dataCriacao ASC";
        $resultado = $this->conexao->query($busca);
        if ($resultado) {
            while ($row = $resultado->fetch_assoc()) {
                $campeonatos[] = $row;
            }
        }
        return $campeonatos;
    }
}
