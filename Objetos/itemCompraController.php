<?php
include_once __DIR__ . "/../configs/database.php";
include_once __DIR__ . "/itemCompra.php";

class ItemCompraController {
    private $bd;
    private $item;

    public function __construct() {
        $banco = new Database();
        $this->bd = $banco->conectar();
        $this->item = new ItemCompra($this->bd);
    }

    public function cadastrarItemCompra($dados) {
        $this->item->DataVal_Item = $dados['DataVal_Item'];
        $this->item->Qtd_Item = $dados['Qtd_Item'];
        $this->item->Valor_Item = $dados['Valor_Item'];
        $this->item->Data_Venda = $dados['Data_Venda'];
        $this->item->NotaFiscal_Entrada = $dados['NotaFiscal_Entrada'];
        $this->item->Cod_CatMed = $dados['Cod_CatMed'];
        $this->item->Cod_Med = $dados['Cod_Med'];

        return $this->item->cadastrar();
    }

    public function lerPorNotaFiscal($notaFiscal) {
        return $this->item->lerPorNotaFiscal($notaFiscal);
    }
}
