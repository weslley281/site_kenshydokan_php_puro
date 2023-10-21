<?php
class GraduacaoModel
{
    private $graduacao;
    private string $dataMudanca;
    private string $dataCriacao;

    public function __construct($graduacao, $dataMudanca)
    {
        $this->graduacao = $graduacao;
        $this->dataMudanca = $dataMudanca;
        $this->dataCriacao = date("Y-m-d");
    }

    public function getGraduacao()
    {
        return $this->graduacao;
    }

    public function getDataMudanca()
    {
        return $this->dataMudanca;
    }

    public function getDataCriacao()
    {
        return $this->dataCriacao;
    }
}
