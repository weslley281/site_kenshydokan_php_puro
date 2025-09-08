<?php
class Graduacao
{
    private $id_graduacao;
    private $graduacao;

    public function __construct($id_graduacao, $graduacao)
    {
        $this->id_graduacao = $id_graduacao;
        $this->graduacao = $graduacao;
    }

    public function getIdGraduacao()
    {
        return $this->id_graduacao;
    }

    public function getGraduacao()
    {
        return $this->graduacao;
    }
}
?>