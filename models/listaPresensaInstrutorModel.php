<?php
class ListaPresensaInstrutorModel
{
    private $id_listaPresensaInstrutor;
    private $id_filiado;
    private $dataPresenca;
    private $status;
    private $conteudoAula;

    public function __construct($id_listaPresensaInstrutor, $id_filiado, $dataPresenca, $status, $conteudoAula)
    {
        $this->id_listaPresensaInstrutor = $id_listaPresensaInstrutor;
        $this->id_filiado = $id_filiado;
        $this->dataPresenca = $dataPresenca;
        $this->status = $status;
        $this->conteudoAula = $conteudoAula;
    }

    public function getIdListaPresensaInstrutor()
    {
        return $this->id_listaPresensaInstrutor;
    }

    public function getIdFiliado()
    {
        return $this->id_filiado;
    }

    public function getDataPresenca()
    {
        return $this->dataPresenca;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getConteudoAula()
    {
        return $this->conteudoAula;
    }
}