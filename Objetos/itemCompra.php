<?php

class ItemCompra {
    public $Cod_Item;
    public $DataVal_Item;
    public $Qtd_Item;
    public $Valor_Item;
    public $Data_Venda;
    public $NotaFiscal_Entrada;
    public $Cod_CatMed;
    public $Cod_Med;

    private $bd;

    public function __construct($bd) {
        $this->bd = $bd;
    }

    public function cadastrar() {
        $sql = "INSERT INTO item (DataVal_Item, Qtd_Item, Valor_Item, Data_Venda, NotaFiscal_Entrada, Cod_CatMed, Cod_Med) 
                VALUES (:dataVal, :qtd, :valor, :dataVenda, :notaFiscal, :codCatMed, :codMed)";
        $stmt = $this->bd->prepare($sql);
        $stmt->bindParam(":dataVal", $this->DataVal_Item, PDO::PARAM_STR);
        $stmt->bindParam(":qtd", $this->Qtd_Item, PDO::PARAM_INT);
        $stmt->bindParam(":valor", $this->Valor_Item, PDO::PARAM_STR);
        $stmt->bindParam(":dataVenda", $this->Data_Venda, PDO::PARAM_STR);
        $stmt->bindParam(":notaFiscal", $this->NotaFiscal_Entrada, PDO::PARAM_INT);
        $stmt->bindParam(":codCatMed", $this->Cod_CatMed, PDO::PARAM_INT);
        $stmt->bindParam(":codMed", $this->Cod_Med, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function lerPorNotaFiscal($notaFiscal) {
        $sql = "SELECT i.*, m.Nome_Med 
                FROM item i 
                JOIN medicamento m ON i.Cod_Med = m.Cod_Med 
                WHERE i.NotaFiscal_Entrada = :notaFiscal";
        $stmt = $this->bd->prepare($sql);
        $stmt->bindParam(":notaFiscal", $notaFiscal, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
